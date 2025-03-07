@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/manager_attendance.css')}}">

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
    <div class="manager-attendance-list">
        <label class="manager-attendance-list-title">{{ $displayDate->format('Y年m月d日') }} の出勤情報</label>
        
            <div class="mb-3">
                
            <a href="{{ url()->current() . '?date=' . $previousDay }}" class="day">←前日</a>
                <div class="year">
                    <img src="/storage/img/image 1.svg" alt="carender" >                 
                    {{ $displayDate->format('Y/m/d') }}</div>
                <a href="{{ url()->current() . '?date=' . $nextDay }}" class="day">翌日→</a>
            </div>  
        
        <table class="attendance">
            <tr>
                <th>名前</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th class="detail">詳細</th>
            </tr>
            @foreach($attendances as $attendance)
            <tr>
                <td>{{ $attendance->user->name }}</td>
                <td>{{ $attendance->punchIn }}</td>
                <td>{{ $attendance->punchOut }}</td>

                
                <td>
                    @if(isset($breaktimesByDate) && is_numeric($breaktimesByDate))
                        {{ gmdate("H:i:s", $breaktimesByDate * 60) }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $attendance->workDuration }}</td>
                <td>
                <a href="{{ url('/admin/attendance/' . $attendance->id) }}" class="detail">詳細</a>
                </td>  
            </tr>
            @endforeach
        </table>
    </div>
@endsection




