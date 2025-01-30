<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth; 


class GeneralController extends Controller
{
    public function index()
    {   
        $now = Carbon::now();
        $now_format = $now->format('Y-m-d');
        $now_time = $now->format('H:i:s');
        $week_num = date('w');
        $week = [
            '日','月','火','水','木','金','土',
        ];
        $day = $week[$week_num];
       
        return view('index',compact('now_format','now_time','day') );
    }

    public function list(Request $request)
    {   
        $year = $request->input('year') ?? Carbon::today()->format('Y');
        $month = $request->input('month') ?? Carbon::today()->format('m');
        $thisMonth = Carbon::Create($year, $month, 01, 00, 00, 00);
        $previousMonth = $thisMonth->copy()->subMonth();
        // 翌月を取得
        $nextMonth = $thisMonth->copy()->addMonth();
        
       $attendances = Attendance::all();

        return view('attendance',compact('attendances'))
            ->with('thisMonth', $thisMonth) 
            ->with('previousMonth', $previousMonth)
            ->with('nextMonth', $nextMonth)
        ;
       
    }

    public function detail($attendances_id)
    {
        $attendances = Attendance::find($attendances_id);
        $users = User::all();
        return view('detail',compact('attendances','users'));
    }

    public function request()
    {
        return view('request');
    }

    public function punchIn(Request $request)
    {   
        $user = Auth::user();
        $oldTimestamp = Attendance::where('user_id', $user->id)->latest()->first();
        if ($oldTimestamp) {
            $oldTimestampPunchIn = new Carbon($oldTimestamp->punchIn);
            $oldTimestampDay = $oldTimestampPunchIn->startOfDay();
        } else {
            $timestamp = Attendance::create([
                'user_id' => $user->id,
                'punchIn' => Carbon::now(),
            ]);

            return redirect('/attendance');
        /**$form = $request->all();
        Attendance::create($form);
        return redirect('/attendance'); **/
        
    }
}
    
}
