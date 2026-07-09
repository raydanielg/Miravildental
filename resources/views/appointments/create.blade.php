@extends('layouts.dashboard')

@section('title', 'Book Appointment - ' . config('app.name'))
@section('page_title', 'Book Appointment')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <form method="POST" action="{{ route('appointments.store') }}">
        @include('appointments._form')
    </form>
</div>
@endsection
