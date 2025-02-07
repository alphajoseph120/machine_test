<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Car;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request){
     
        $request->validate([
            'car_id'=>'required',
            'user_id'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
        ]);

        $isCarBooked = Booking::where('car_id', $request->car_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->exists();

        if ($isCarBooked) {
            return response()->json([
                'code' => 400,
                'status' => 'error',
                'message' => 'Car is not available for the selected dates',
            ]);
        }

        $car = Car::findorfail($request->car_id);
        $daily_rent = $car->price_per_day;

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $totalDays = $start->diffInDays($end) + 1;

        $totalPrice = 0;

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $priceForDay = $daily_rent;

            if ($date->isSaturday() || $date->isSunday()) {
                $priceForDay *= 10;
            }

            $totalPrice += $priceForDay;
        }

            $booking = Booking::create([
                'user_id'=> $request->user_id,
                'car_id'=>$request->car_id,
                'start_date'=>$request->start_date,
                'end_date'=>$request->end_date,
                'total_price'=>$totalPrice,
            ]);

            return response()->json([
                'code'    => 200,
                'status'  => 'success',
                'message' => 'Booking Created Successfully',
                'rent_amount'=>$totalPrice,
            ]);
        }
}
