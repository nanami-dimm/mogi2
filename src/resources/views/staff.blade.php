@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/staff.css')}}">

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
<div class="staff">
    <div class="staff-list">
        <label class="staff-list-title">スタッフ一覧</label>
    </div>
    <div class="staff-list-inner">
        <table class="staff-list-table">
            <tr>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>月次勤怠</th>   
            </tr>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td><a href="/admin/attendance/staff/{{$user->id ?? '#' }}">詳細</td>

            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection

    