@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
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
<script>
var today=new Date(); 


var year = today.getFullYear();
var month = today.getMonth()+1;
var week = today.getDay();
var day = today.getDate();

var week_ja= new Array("日","月","火","水","木","金","土");


document.write(year+"年"+month+"月"+day+"日 "+"("+week_ja[week]+")");
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

    <form class="work-form" method="post">
    @csrf
    <input class="work-form__btn" type="submit" value="出勤">
@endsection('content')