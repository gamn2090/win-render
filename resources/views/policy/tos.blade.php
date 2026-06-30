<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>WIN: Terms of Service</title>
  
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
  @include('components.fonts')
</head>
@if(Auth::guard('vendor')->check())
    @include('layouts.vendor_navigation')
@elseif(Auth::guard('web')->check())
    @include('layouts.navigation')
@else
    @include('layouts.guest_navigation')
@endif

<body class="bg-win-light overflow-x-hidden">
    
</body>