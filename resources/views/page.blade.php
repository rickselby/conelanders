@extends('outline')

@section('body')
  {!! Breadcrumbs::renderIfExists() !!}
  @yield('header')
  {!! Notification::showAll() !!}
  @include('formResponse')
  @yield('content')
  @include('tablesorter')
@endsection
