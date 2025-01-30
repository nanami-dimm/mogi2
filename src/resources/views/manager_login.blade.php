@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/manager_login.css')}}">
@endsection

@section('link')

@endsection

@section('content')
<div class="admin-login-form">
  <h2 class="admin-login-form__heading content__heading">管理者ログイン</h2>
  <div class="admin-login-form__inner">
    <form class="admin-login-form__form" action="/admin/login" method="post">
      @csrf
      <div class="admin-login-form__group">
        <label class="admin-login-form__label" for="email">メールアドレス</label>
        <input class="admin-login-form__input" type="mail" name="email" id="email" >
        <p class="admin-login-form__error-message">
          @error('email')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="admin-login-form__group">
        <label class="admin-login-form__label" for="password">パスワード</label>
        <input class="admin-login-form__input" type="password" name="password" id="password" >
        <p class="admin-login-form__error-message">
          @error('password')
          {{ $message }}
          @enderror
        </p>
      </div>
      <input class="admin-login-form__btn btn" type="submit" value="管理者ログインする">
      
    </form>
  </div>
</div>
@endsection('content')