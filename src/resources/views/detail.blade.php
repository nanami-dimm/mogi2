@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css')}}">
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
            勤怠詳細</label>
            <div class="attendance-detail-inner">
            
                        <label class="detail_label" >名前</label>
                        @foreach($users as $user)
                        <label class="detail_name">{{ $user-> name }}</label>
                        @endforeach

                       <form action="/attendance" method="post">
                        @csrf 
                            <label class="detail_label" for="date">日付</label>
                            <input class="detail_input" id="date" name="date" type="text" value="{{ $attendances->date_column }}">
                        
                            <label class="detail_label" for="start-work">出勤・退勤</label>
                            <input class="detail_input" id="start-work" name="start-work" type="text" value="{{ $attendances->punchIn }}">
                            <label>~</label>
                            <input class="detail_input" id="end-work" name="end-work" type="text" value="{{ $attendances->punchOut }}">

                            <label class="detail_label" for="rest-time">休憩</label>
                            <input class="detail_input" id="rest-start" name="rest-start" type="text" value="{{ $attendances->breakStart }}">
                            <label>~</label>
                            <input class="detail_input" id="rest-end" name="rest-end" type="text" value="{{ $attendances->breakEnd }}">

                            <label class="detail_label" for="note">備考</label>
                            <textarea class="detail_note" id="note" name="note" cols="30" rows="10">
                            </textarea>

                            <button class="fix-button">修正 </button>
        
                        </form>
                </div>
    </div>
</div>
@endsection

                        
