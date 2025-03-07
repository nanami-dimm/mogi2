@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/staff_attendance.css')}}">

@endsection

@section('link')
<div class="toppage-header">
    
    
        <button class="attendance__link" onclick="location.href='/admin/attendance/list'">勤怠一覧</button>

        <button class="attendance_list__list" onclick="location.href='/admin/staff/list'">スタッフ一覧</button>
        
        <button class="request__link" onclick="location.href='/stamp_correction_request/list'">申請一覧</button>

        @if (Auth::check())
    
        <form action="/logout" method="post" class="logout-form">
        @csrf
            <button class="logout-button">ログアウト</button>
        </form>
        @endif
    </div>
    @endsection
    @section('content')
    <div class="staff-attendance">
        <div class="staff-name">
            <label class="name">{{ $user->name }}さんの勤怠</label>
        <div class="year-month">
            <a href="{{ url()->current() . '?year=' . $previousMonth->format('Y') . '&month=' . $previousMonth->format('m') }}" class="month">←前月</a>
                <div class="year">
                    <img src="/storage/img/image 1.svg" alt="carender" >                 
                    {{ $thisMonth->format('Y/m') }}</div>
                <a href="{{ url()->current() . '?year=' . $nextMonth->format('Y') . '&month=' . $nextMonth->format('m') }}" class="month">翌月→</a>
            </div>
        </div>
        <div class="attendance-time">
                <table class="time-detail">
                    <tr>
                        <th>日付</th>
                        <th>出勤</th>
                        <th>退勤</th>
                        <th>休憩</th>
                        <th>合計</th>
                        <th class="detail">詳細</th>
                    </tr>
                    @foreach($daysInMonth as $day)
                    <tr>
                        <td>{{ $day }}</td>
                        @if(isset($attendancesByDate[$day]) && $attendancesByDate[$day]->isNotEmpty())
                        @foreach($attendancesByDate[$day] as $attendance)
                        <td>{{ $attendance->punchIn }}</td>
                        <td>{{ $attendance->punchOut }}</td>
                        <td>
                                @if(isset($breaktimesByDate[$day]))
                                    {{ gmdate("H:i:s", $breaktimesByDate[$day] * 60) }}
                                @else
                                    -
                                @endif
                        </td>
                        
                        <td>
                        {{ isset($attendancesByDate[$day]) && $attendancesByDate[$day]->isNotEmpty() ? 
        gmdate("H:i:s", $attendancesByDate[$day]->first()->workDuration * 60) : '-' }}
                        </td>
                        <td><a href="{{ url('/attendance/' . $attendance->id) }}" class="detail">詳細</a></td>
                        @endforeach
                        @else
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        @endif
                    </tr>
                    @endforeach
                        
                </table>
                <div class="csv-btn">
                    <button class="csv">CSV出力</button>
        </div>
    </div>
</div>
@endsection