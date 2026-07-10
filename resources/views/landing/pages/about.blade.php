@extends('landing.layout')

@section('title', 'About Us | Miravil Specialised Dental Centre - Mwanza, Tanzania')
@section('meta_description', 'Learn about Miravil Specialised Dental Centre in Mwanza, Tanzania. Our modern clinic offers advanced diagnostics, expert dentists, and compassionate dental care.')
@section('meta_keywords', 'About Miravil Dental, dental clinic Mwanza, dentist Tanzania, modern dental clinic, dental specialists')
@section('og_image', asset('images.png'))

@section('content')
    @include('landing.partials.about')
@endsection
