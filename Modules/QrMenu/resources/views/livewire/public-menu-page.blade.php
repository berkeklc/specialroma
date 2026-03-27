@extends('qrmenu::layouts.qr-menu')

@section('content')
    @livewire('qr-menu::public-menu', ['restaurant' => $restaurant, 'tableId' => $tableId])
@endsection
