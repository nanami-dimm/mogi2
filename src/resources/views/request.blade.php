@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/request.css')}}">
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
<div class="request-list">
    <div class="request-list-title">
        <h2 class="request-list-heading">申請一覧</h2>
    </div>
    <div class="confirm">
        <ul class="confirm_list">
        <li><a href="{{ url()->current() }}?status=apply" >承認待ち</a></li>
            <li><a href="{{ url()->current() }}?status=agree" >承認済み</a></li>
        </ul>
    </div>
    <div class="list">
        <div class="request">
            <table class="request-detail">
                <tr>
                    <th>状態</th>
                    <th>名前</th>
                    <th>対象日時</th>
                    <th>申請理由</th>
                    <th>申請日時</th>
                    <th class="detail">詳細</th>
                </tr>
                @foreach($applies as $apply)
                <tr>
                    @foreach($requests as $request)
                    <td>{{ $request->status == 'apply' ? '承認待ち' : '承認済み' }}</td>
                    @endforeach
                    
                    <td>{{ $users->name }}</td>
                    <td>{{  $apply->date }}</td>
                    <td>{{ $apply->note }}</td>
                    <td>{{ $apply->created_at->format('Y-m-d') }}</td>
                    <td><a href="/attendance/{{ $apply->id }}">詳細</a></td>
                @endforeach
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection