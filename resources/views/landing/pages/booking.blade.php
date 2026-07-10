@extends('landing.layout')

@section('title', 'Book Appointment | Miravil Specialised Dental Centre - Mwanza, Tanzania')
@section('meta_description', 'Book your dental appointment online at Miravil Specialised Dental Centre in Mwanza, Tanzania. Fast, easy booking for all dental services.')
@section('meta_keywords', 'book dentist appointment Mwanza, dental booking Tanzania, online dental appointment, Miravil Dental booking')
@section('og_image', asset('images.png'))

@section('content')
    @include('landing.partials.appointment')
@endsection
