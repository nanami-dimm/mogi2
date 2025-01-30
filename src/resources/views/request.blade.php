@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/request.css')}}">
@endsection

@section('link')
<div class="toppage-header">
    
    <div class="toppage-header-nav">
        <a class="attendance__link" href="/">勤怠</a>
        <a class="attendance_list__list" href="/attendance/list">勤怠一覧</a>
        <a class="request__link" href="/stamp_correction_request/list">申請</a>
    @if (Auth::check())
    
        <form action="/logout" method="post" class="logout-form">
        @csrf
            <button class="logout-button">ログアウト</button>
        </form>
      
        
    @endif
        
    
</div>
@endsection

@section('content')
<div class="request-list">
    <div class="request-list-title">
        <h2 class="request-list-heading">申請一覧</h2>
    </div>
    <div class="confirm">
        <input class="list-btn" type="submit" value="承認済み">
        <input class="list-btn" type="submit" value="承認済み">
    </div>
    <div class="list">
    </div>
@endsection