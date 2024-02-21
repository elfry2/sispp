@extends('layouts.form')
@section('content')
    <h5 class="mt-5">{{ $title }}!</h5>
    <p>Laporan dapat diunduh di <a href="{{ $url }}" target="_blank">sini</a>.</p>
@endsection
