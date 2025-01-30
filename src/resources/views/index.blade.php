@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">

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
<p id="date-display"></p>
<script>
var today=new Date(); 


var year = today.getFullYear();
var month = today.getMonth()+1;
var week = today.getDay();
var day = today.getDate();

var week_ja= new Array("日","月","火","水","木","金","土");

var dateString = year + "年"　+ month + "月" + day + "日(" + week_ja[week] + ")";

document.getElementById("date-display").innerText = dateString;
</script>

<p id="realtime"></p>
<script>
    function showClock() {
      let nowTime = new Date();
      let nowHour = nowTime.getHours();
      let nowMin  = nowTime.getMinutes();
      let nowSec  = nowTime.getSeconds();
      let msg =  nowHour + ":" + nowMin + ":" + nowSec;
      document.getElementById("realtime").innerHTML = msg;
    }
    setInterval('showClock()',1000);
  </script>


    <div class="laravel-time">
        <div id="current-date">{{ $now_format }}({{$day}})</div>
        <div id="current-time">{{ $now_time }}</div>
        

    

    <form class="work-form" method="post" action="/punchin">
    @csrf
    <input id="work-form__btn" type="submit" value="出勤">
    </form>

    <div class="finish-work">
        <form class="finish-work-form" method="post" action="/punchout">
            @csrf
            <input id="finish-work__btn" type="submit" value="退勤">
        </form>
    </div>

    <div class="rest-in">
        <form class="rest-in-form" method="post" action="/restin">
            @csrf
            <input id="rest-in__btn" type="submit" value="休憩入">
        </form>
    </div>
    <div class="rest-out">
        <form class="rest-out-form" method="post" action="/restout">
            @csrf
            <input id="rest-out__btn" type="submit" value="休憩戻">
        </form>
    </div>
    <div class="after-finish-work">
        <label id="after-finish-work-word">お疲れ様でした。</label>
    </div>
@endsection('content')