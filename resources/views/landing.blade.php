@extends('layouts.guest')

@section('title', 'Lavanderie')

@section('content')
    <div class="landing-page">

        @include('sections._hero')
        @include('sections._about')
        @include('sections._staff')
        @include('sections._services')

    </div>
@endsection