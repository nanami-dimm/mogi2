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
        
    
    <div id="attendance">
        <button id="work-form__btn"  onclick="recordTime('punchIn')">出勤</button>

        <button id="rest-in__btn" onclick="recordTime('breakStart')" style="display: none;">休憩入</button>

        <button id="rest-out__btn" onclick="recordTime('breakEnd')" style="display: none;">休憩戻</button>
        
        <button id="finish-work__btn" onclick="recordTime('punchOut')" style="display: none;">退勤</button>
        
        
    </div>
    <div id="message" style="display: none;">お疲れ様でした。</div>
        
    
</div>
<script src="{{ asset('js/script.js') }}"></script>

@endsection