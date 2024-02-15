@extends('layouts.dashboard')
@section('topnav')
@endsection
@section('bottomnav')
@endsection
@section('content')
@if ($primary->count() == 0)
@include('components.no-data-text')
@else
<div class="">
   <div class="d-flex">
       @foreach($primary as $card)
       <div class="card ms-3 mt-3" style="width: 16em; height: 10em">
           <div class="card-body">
               <h1>{{ $card->content }}</h1>
               <p>{{ $card->title }}</p>
           </div>
       </div>
       @endforeach
   </div>
</div>
@endif
@endsection
