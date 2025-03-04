@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/manager_request.css')}}">

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
    <div class="manager-request">
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
                @foreach($requests as $request)
                <tr>
                    
                    <td>{{ $request->status == 'apply' ? '承認待ち' : '承認済み' }}</td>
                    
                    
                    <td>{{ $users->name }}</td>
                    <td>{{ $request->date }}</td>
                    <td>{{ $request->note }}</td>
                    <td>{{ $request->created_at->format('Y-m-d') }}</td>
                    <td><a href="/admin/attendance/{{ $request->id }}">詳細</a></td>
                    
                @endforeach
                </tr>
            </table>
        </div>
    </div>
</div>
    @endsection