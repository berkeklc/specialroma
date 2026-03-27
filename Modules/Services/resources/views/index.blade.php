@extends('services::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('services.name') !!}</p>
@endsection
