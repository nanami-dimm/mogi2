<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Breaktime;
use App\Models\Apply;
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

        $breakDuration = $end->diffInMinutes($start); // 休憩時間を分単位で計算

        if (!isset($breaktimesByDate[$date])) {
            $breaktimesByDate[$date] = 0; // 初めての休憩時間
        }

        $breaktimesByDate[$date] += $breakDuration; // 同じ日に複数の休憩があれば加算
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

    public function request(Request $request)
    {   
        // クエリパラメータから status を取得
        $status = $request->input('status', 'apply');

        $requests = Apply::where('status', $status)->get();

        // 申請データの取得クエリ
        $query = Apply::query();

        if ($status) {
        $query->where('status', $status); // ステータスでフィルタリング
        }

        $applies = $query->get();
        $users = User::find(Auth::id());

        return view('request',compact('applies','users','requests'));
    }

    public function saveTime(Request $request)
{
    $user = Auth::user();
    $type = $request->input('type');
    $time = Carbon::parse($request->input('time'));

    // 出勤の場合
    if ($type === 'punchIn') {
        // 新しい Attendance インスタンスを作成
        $attendance = new Attendance();
        $attendance->user_id = $user->id;
        $attendance->punchIn = $time->toTimeString();
        $attendance->save(); // 出勤時間を保存

    // 休憩開始の場合
    } elseif ($type === 'breakStart') {
        // 最新の Attendance を取得
        $attendance = Attendance::where('user_id', $user->id)
            ->whereNull('punchOut') // 退勤していない出勤レコードを取得
            ->latest()
            ->first();

        // 休憩データを作成
        $breakTime = BreakTime::create([
            'breakStart' => $time,
            'breakEnd' => null,
        ]);

        // 中間テーブルに関連付けを保存
        $attendance->breakTimes()->attach($breakTime->id);

    // 休憩終了の場合
    } elseif ($type === 'breakEnd') {
        // 最新の Attendance を取得
        $attendance = Attendance::where('user_id', $user->id)
            ->whereNull('punchOut') // 退勤していない出勤レコードを取得
            ->latest()
            ->first();

        // 最新の休憩データを取得
        $latestBreak = $attendance->breakTimes()
            ->whereNull('breakEnd')
            ->latest()
            ->first();

        if ($latestBreak) {
            $latestBreak->breakEnd = $time;
            $latestBreak->save();
        }

    // 退勤の場合
    } elseif ($type === 'punchOut') {
        // 最新の Attendance を取得
        $attendance = Attendance::where('user_id', $user->id)
            ->whereNull('punchOut') // 退勤していない出勤レコードを取得
            ->latest()
            ->first();

        // 退勤時間を保存
        $attendance->punchOut = $time->toTimeString();
        $attendance->save();
    }

    return response()->json([
        'message' => '時間を保存しました',
        'type' => $type,
        'time' => $time->toTimeString(),
    ]);
}

    

    public function postrequest(Request $request)
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
