@extends('landing.layout')

@section('title', 'Our Services | Miravil Specialised Dental Centre - Mwanza, Tanzania')
@section('meta_description', 'Explore our dental services at Miravil Specialised Dental Centre: teeth whitening, root canal, orthodontics, cosmetic dentistry, prosthodontics, and more in Mwanza, Tanzania.')
@section('meta_keywords', 'dental services Mwanza, teeth whitening, root canal, orthodontics, cosmetic dentistry, dental implants, Miravil Dental')
@section('og_image', asset('images.png'))

@section('content')
    @include('landing.partials.services')
@endsection
