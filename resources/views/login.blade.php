@extends('page')

@section('header')
<div class="page-header">
    <h1>Login</h1>
</div>
@endsection

@section('content')
<p>
    <a class="btn btn-social btn-google" href="<?=route('login.google') ?>">
        <span class="fa fa-google"></span> Sign in with Google
    </a>
</p>
@endsection
