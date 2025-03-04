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

    // 今日の日付を取得
        $today = Carbon::today()->toDateString();

    // 表示する日付（クエリパラメータで指定された日付を使用）
        $displayDate = Carbon::parse($date);

    // 前日・翌日を計算
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

        $breakDuration = $end->diffInMinutes($start); // 休憩時間を分単位で計算

        if (!isset($breaktimesByDate[$date])) {
            $breaktimesByDate[$date] = 0; // 初めての休憩時間
        }

        $breaktimesByDate[$date] += $breakDuration; // 同じ日に複数の休憩があれば加算
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
        // クエリパラメータから status を取得
        $status = $request->input('status', 'apply');

        if ($status === 'apply') {
        $requests = Apply::where('status', $status)->get();
        } elseif ($status === 'agree') {
        $requests = Agree::all(); // Agreesテーブルのデータを取得
        } else {
        $requests = collect(); // 空のコレクションを作成
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

        $breakDuration = $end->diffInMinutes($start); // 休憩時間を分単位で計算

        if (!isset($breaktimesByDate[$date])) {
            $breaktimesByDate[$date] = 0; // 初めての休憩時間
        }

        $breaktimesByDate[$date] += $breakDuration; // 同じ日に複数の休憩があれば加算
        }
        //dd($breaktimesByDate);

        $attendances = Attendance::whereYear('created_at', $thisMonth->year)->whereMonth('created_at', $thisMonth->month)->get();

        $attendancesByDate = [];

foreach ($daysInMonth as $day) {
    // 各ユーザーの勤怠データを日付ごとにフィルタリング
    $attendancesByDate[$day] = Attendance::whereDate('created_at', $day)
        ->where('user_id', $user->id)  // ユーザーごとにフィルタリング
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
        'status'  => $request->input('status', 'apply'),  // デフォルト値 'apply'
    ]);

        return redirect('/admin/attendance/list');
        
    }

    public function agree($id){
        $apply = Apply::find($id);

    // データが見つからなければエラー
    
    $agree = new Agree();
    $agree->user_id = $apply->user_id;
    $agree->status = 'agree'; // 承認されたことを示す
    $agree->date = $apply->date;
    $agree->punchIn = $apply->punchIn;
    $agree->punchOut = $apply->punchOut;
    $agree->reststart = $apply->reststart;
    $agree->restend = $apply->restend;
    $agree->note = $apply->note;
    $agree->created_at = now();
    $agree->updated_at = now();
    $agree->save();

    // `apply` テーブルから削除
    $apply->delete();

    return redirect('/admin/attendance/list');
    }

    }

