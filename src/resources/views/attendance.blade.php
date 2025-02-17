@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css')}}">
@endsection

@section('link')
<div class="toppage-header">
    
    <div class="toppage-header-nav">
        <button class="attendance__link" onclick="location.href='/attendance'">勤怠</button>
        <button class="attendance_list__list" onclick="location.href='/attendance/list'">勤怠一覧</button>
        
        <button class="request__link" onclick="location.href='/stamp_correction_request/list'">申請</button>
    @if (Auth::check())
    
        <form action="/logout" method="post" class="logout-form">
        @csrf
            <button class="logout-button">ログアウト</button>
        </form>
      
        
    @endif
        
    
</div>
@endsection


    @section('content')
    <div class="attendance-list">
        <label class="attendance-list-title">
            勤怠一覧</label>
        <div class="attendance-list-inner">
            <div class="select-year-month">
                <a href="{{ url()->current() . '?year=' . $previousMonth->format('Y') . '&month=' . $previousMonth->format('m') }}" class="month">←前月</a>
                <div class="year">
                    <img src="/storage/img/image 1.svg" alt="carender" >                 
                    {{ $thisMonth->format('Y/m') }}</div>
                <a href="{{ url()->current() . '?year=' . $nextMonth->format('Y') . '&month=' . $nextMonth->format('m') }}" class="month">翌月→</a>
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
                            @if(isset($attendancesByDate[$day]))
                            <td>{{ $attendancesByDate[$day]->punchIn }}</td>
                            <td>{{ $attendancesByDate[$day]->punchOut }}</td>
                            
                            
                        @else
                            <td>-</td> 
                            <td>-</td>
                        @endif

                        
                        <td>
                                @if(isset($breaktimesByDate[$day]))
                                    {{ gmdate("H:i:s", $breaktimesByDate[$day] * 60) }}
                                @else
                                    -
                                @endif
                        </td>
                        
                        <td>
                            @if(isset($attendancesByDate[$day]))
                                {{ gmdate("H:i:s",$attendancesByDate[$day]->workDuration *60) }} 
                            @else
                                —
                            @endif
                        </td>
                        
                        <td><a href="/attendance/{{ $attendancesByDate[$day]->id ?? '#' }}" class="detail">詳細</a></td> 
                    </tr>
                        
                        @endforeach
                    
                    
            </div>
        
    
</div>
@endsection


