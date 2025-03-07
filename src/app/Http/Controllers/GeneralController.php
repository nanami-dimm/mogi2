<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Breaktime;
use App\Models\Apply;
use App\Http\Requests\ApplyRequest;
use Illuminate\Support\Facades\Auth; 
use App\Models\Agree;


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
        $year = intval($request->input('year') ?? Carbon::today()->format('Y'));
        
        $month = str_pad(intval($request->input('month') ?? Carbon::today()->format('m')), 2, '0', STR_PAD_LEFT);
        
    
        $thisMonth = Carbon::create($year, $month, 1);
        //dd($thisMonth);

        $previousMonth = $thisMonth->copy()->subMonth();
        //dd($previousMonth);

        $nextMonth = $thisMonth->copy()->addMonth();
        //dd($nextMonth);

         $lastDay = $thisMonth->copy()->lastOfMonth();

        $daysInMonth = [];
        for ($date = $thisMonth->copy(); $date->lte($lastDay); $date->addDay()) {
        $daysInMonth[] = $date->format('Y-m-d'); 
        }

        $breaktimes = Breaktime::all();
        $breaktimesByDate = [];

        foreach ($breaktimes as $breaktime) {
        $date = Carbon::parse($breaktime->breakStart)->format('Y-m-d'); 
        //dd($date);
        $start = Carbon::parse($breaktime->breakStart);
        $end = Carbon::parse($breaktime->breakEnd);

        $breakDuration = $end->diffInMinutes($start); 

        if (!isset($breaktimesByDate[$date])) {
            $breaktimesByDate[$date] = 0;
        }

        $breaktimesByDate[$date] += $breakDuration; 
        }
        //dd($breaktimesByDate);

        $attendances = Attendance::whereYear('created_at', $thisMonth->year)->whereMonth('created_at', $thisMonth->month)->get();

        $attendancesByDate = [];
        foreach ($attendances as $attendance) {
        $attendancesByDate[$attendance->created_at->format('Y-m-d')] = $attendance;
        }

        return view('attendance',compact('attendancesByDate','attendances','breaktimesByDate','daysInMonth',))
            ->with('thisMonth', $thisMonth) 
            ->with('previousMonth', $previousMonth)
            ->with('nextMonth', $nextMonth)
        ;
       
    }

    public function detail($attendances_id)
    {
        $attendances = Attendance::find($attendances_id);

       
       $breaktimes = Breaktime::find($attendances_id);
        
       

        //dd($breaktimes);

        $users = User::find(Auth::id());
        //dd($users);

        return view('detail',compact('attendances','users','breaktimes'));
    }

    public function apply(Request $request)
    {   
       
        $status = $request->input('status', 'apply');

        if ($status === 'apply') {
        $requests = Apply::where('status', $status)->get();
        } elseif ($status === 'agree') {
        $requests = Agree::all(); 
        } else {
        $requests = collect(); 
        }

    
        $query = Apply::query();
        $applies = $query->get();
        $apply = $query->first();
        $users = User::find(Auth::id());
        

        return view('request',compact('applies','users','requests','users','status'));
    }

    public function saveTime(Request $request)
{
    $user = Auth::user();
    $type = $request->input('type');
    $time = Carbon::parse($request->input('time'));

    
    if ($type === 'punchIn') {
        

        $attendance = new Attendance();
        $attendance->user_id = $user->id;
        $attendance->punchIn = $time->toTimeString();
        $attendance->save(); 
        \Log::info("New Attendance Created: ", $attendance->toArray()); 
    
    } elseif ($type === 'breakStart') {
        
        $attendance = Attendance::where('user_id', $user->id)
            ->whereNull('punchOut') 
            ->latest()
            ->first();

        
        $breakTime = BreakTime::create([
            'breakStart' => $time,
            'breakEnd' => null,
        ]);

        
        $attendance->breakTimes()->attach($breakTime->id);

    
    } elseif ($type === 'breakEnd') {
       
        $attendance = Attendance::where('user_id', $user->id)
            ->whereNull('punchOut') 
            ->latest()
            ->first();

        
        $latestBreak = $attendance->breakTimes()
            ->whereNull('breakEnd')
            ->latest()
            ->first();

        if ($latestBreak) {
            $latestBreak->breakEnd = $time;
            $latestBreak->save();
        }

    
    } elseif ($type === 'punchOut') {
        
        $attendance = Attendance::where('user_id', $user->id)
            ->whereNull('punchOut') 
            ->latest()
            ->first();

        $attendance->punchOut = $time->toTimeString();
        $attendance->save();
        $latestBreak = $attendance->breakTimes()
            ->whereNull('breakEnd')
            ->orderBy('breaktimes.id', 'desc')
            ->first();

    }

    return response()->json([
        'message' => '時間を保存しました',
        'type' => $type,
        'time' => $time->toTimeString(),
    ]);
}

    

    public function postrequest(ApplyRequest $request)
    {   
        $form = $request->all();
        $form['user_id'] = Auth::id();
        Apply::create($form);
        Apply::create([
            'status' => 'apply'
        ]);

        return redirect('/attendance');
        
    }
    }
