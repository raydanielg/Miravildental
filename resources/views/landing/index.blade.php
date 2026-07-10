@extends('landing.layout')

@section('title', 'Miravil Specialised Dental Centre | Best Dental Clinic in Mwanza, Tanzania')
@section('meta_description', 'Miravil Specialised Dental Centre offers advanced dental care, teeth whitening, root canal, orthodontics, and family dentistry in Mwanza, Tanzania. Book online today.')
@section('meta_keywords', 'Miravil Dental, dental clinic Mwanza, dentist Tanzania, teeth whitening, root canal, orthodontics, family dentist, dental care')
@section('og_image', asset('images.png'))

@section('content')
    @include('landing.partials.hero')
    @include('landing.partials.features')
    @include('landing.partials.about')
    @include('landing.partials.services')
    @include('landing.partials.appointment')
    @include('landing.partials.contact')
@endsection
