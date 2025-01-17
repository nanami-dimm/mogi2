@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css')}}">
@endsection

@section('link')

@endsection

@section('content')
<div class="register-form">
  <h2 class="register-form__heading content__heading">会員登録</h2>
  <div class="register-form__inner">
    <form class="register-form__form" action="/register" method="post">
      @csrf
      <div class="register-form__group">
        <label class="register-form__label" for="name">名前</label>
        <input class="register-form__input" type="text" name="name" id="name" >
        <p class="register-form__error-message">
          @error('name')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="register-form__group">
        <label class="register-form__label" for="email">メールアドレス</label>
        <input class="register-form__input" type="mail" name="email" id="email" >
        <p class="register-form__error-message">
          @error('email')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="register-form__group">
        <label class="register-form__label" for="password">パスワード</label>
        <input class="register-form__input" type="password" name="password" id="password" >
        <p class="register-form__error-message">
          @error('password')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="register-form__group">
        <label class="register-form__label" for="password">パスワード確認</label>
          <input class="register-form__input" type="password" name="password_confirmation" id="password_confirmation">
            <p class="register-form__error-message">
                @error('comfirm-password')
                {{ $message }}
                @enderror
            </p>
      </div>
      <input class="register-form__btn btn" type="submit" value="登録する">
      <div class="login__link">
        <a class="header__link" href="/login">ログインはこちら</a>
      </div>
    </form>
  </div>
</div>
@endsection('content')