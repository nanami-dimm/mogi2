<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Illuminate\Support\Facades\Redirect;
use App\Models\Breaktime;
use App\Models\User;
use App\Models\Apply;
use App\Models\Agree;

class AdminController extends Controller
{

    public function login()
    {
    return view('manager_login');
    }

public function store(Request $request)
    {
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            
            $user = Auth::user();
            
            
            if ($user->role === 'admin') {
                return redirect('admin/attendance/list');  
            }

            
            return redirect('/attendance/list');  
        }
    }

    public function index(Request $request)
    {   
        $date = $request->query('date', Carbon::today()->toDateString());

    
        $today = Carbon::today()->toDateString();
        $displayDate = Carbon::parse($date);
        $previousDay = $displayDate->copy()->subDay()->format('Y-m-d');
        $nextDay = $displayDate->copy()->addDay()->format('Y-m-d');
        

        $attendances = Attendance::whereDate('created_at',$date)->with(['user','breakTimes'])->get();
        //dd($attendances);

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
        return view('manager_attendance',compact('date','attendances','previousDay','nextDay','breaktimesByDate','today','displayDate'));
    }

    public function staff()
    {   
        $users = User::all();

        return view('staff',compact('users'));
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
        
        

        return view('manager_request',compact('applies','users','requests','status'));
    }
        

    public function staffdetail(Request $request,$id)
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

        $user = User::find($id);

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

foreach ($daysInMonth as $day) {
    
    $attendancesByDate[$day] = Attendance::whereDate('created_at', $day)
        ->where('user_id', $user->id)  
        ->with(['user', 'breakTimes'])
        ->get();
}
        //dd($attendancesByDate);

        return view('staff_attendance',compact('thisMonth','previousMonth','nextMonth','user','daysInMonth','attendances','breaktimesByDate','attendancesByDate'));
    }

    public function attendancedetail($id){
        
        $attendances = Attendance::find($id);
        //dd($attendances);
       
       $breaktimes = Breaktime::find($id);
        

        //dd($breaktimes);

        $users = User::find(Auth::id());
        //dd($users);
        $query = Apply::query();
        $apply = $query->first();
        $applies = $query->get();

        $isApplyStatus = optional($apply)->status === 'apply';

        return view('manager_attendance_detail',compact('attendances','breaktimes','users','isApplyStatus','applies'));
    }

    public function staffrequest(Request $request)
    {   
        $userId = $request->input('user_id');
        Apply::create([
        'user_id' => $userId,  
        'status'  => $request->input('status', 'apply'),  
    ]);

        return redirect('/admin/attendance/list');
        
    }

    public function agree($id){
        $apply = Apply::find($id);
        dd($apply);

    
    
    $agree = new Agree();
    $agree->user_id = $apply->user_id;
    $agree->status = 'agree'; 
    $agree->date = $apply->date;
    $agree->punchIn = $apply->punchIn;
    $agree->punchOut = $apply->punchOut;
    $agree->reststart = $apply->reststart;
    $agree->restend = $apply->restend;
    $agree->note = $apply->note;
    $agree->created_at = now();
    $agree->updated_at = now();
    $agree->save();


    $today = Carbon::today()->toDateString();
    $attendance = Attendance::where('user_id', $apply->user_id)
                            ->whereDate('punchIn', $today)
                            ->first();
    
        $attendance->punchIn = $apply->punchIn;
        $attendance->punchOut = $apply->punchOut;
        $attendance->updated_at = now();
        $attendance->save();
    

    
    $breakTime = new BreakTime();
    $breakTime->attendance_id = $attendance->id;
    $breakTime->breakStart = $apply->reststart; 
    $breakTime->breakEnd = $apply->restend; 
    $breakTime->save();

    return redirect('/admin/attendance/list');
    }

    }

