@extends('layouts.app')

@section('title', 'Data Analyst App')

@section('content')
    <h1>Welcome to My Custom Page</h1>
    <a href="{{route('summary')}}">summary</a>
    <!-- Rest of your content here -->
@endsection
