@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/manager_attendance_detail.css')}}">

@endsection

@section('link')
<div class="toppage-header">
    
    <div class="toppage-header-nav">
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
    <div class="attendance-list">
        <label class="attendance-list-title">勤怠詳細</label>
        <div class="attendance-detail-inner">
            <label class="detail_label">名前</label>
            <label class="detail_name">{{ $users->name }}</label>

            <form action="/admin/attendance/list" method="post">
                @csrf 
                <label class="detail_label" for="date">日付</label>
                <input class="detail_input" id="date" name="date" type="text" value="{{ $attendances->created_at->format('Y-m-d') }}">
            
                <label class="detail_label" for="start-work">出勤・退勤</label>
                <input class="detail_input" id="start-work" name="punchIn" type="text" value="{{ $attendances->punchIn }}">
                <label>~</label>
                <input class="detail_input" id="end-work" name="punchOut" type="text" value="{{ $attendances->punchOut }}">

                <label class="detail_label" for="rest-time">休憩</label>
                <input class="detail_input" id="rest-start" name="reststart" type="text" value="{{ $breaktimes->breakStart }}">
                <label>~</label>
                <input class="detail_input" id="rest-end" name="restend" type="text" value="{{ $breaktimes->breakEnd }}">

                
                <label class="detail_label" for="note">備考</label>
                <textarea class="detail_note" id="note" name="note" cols="30" rows="10"></textarea>
               
                
                {{-- apply の場合は修正不可 --}}
                @if (!$isApplyStatus)
                    <button type="submit" class="fix-button">修正</button>
                @endif
            </form>

            {{-- apply の場合、承認ボタンを表示 --}}
            @if ($isApplyStatus)
                <form action="{{ url('/admin/attendance/approve/' . $attendances->id) }}" method="POST">
                    @csrf
                    
                    <button type="submit" class="btn btn-success">承認する</button>
                </form>
            @endif
        </div>
    </div>
@endsection