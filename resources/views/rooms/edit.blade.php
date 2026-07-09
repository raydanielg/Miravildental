@extends('layouts.dashboard')

@section('title', 'Edit Room - ' . config('app.name'))
@section('page_title', 'Edit Room / Chair')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <form method="POST" action="{{ route('rooms.update', $room) }}">
        @method('PUT')
        @include('rooms._form')
    </form>
</div>
@endsection
