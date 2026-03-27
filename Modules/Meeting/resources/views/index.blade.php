@extends('meeting::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('meeting.name') !!}</p>
@endsection
