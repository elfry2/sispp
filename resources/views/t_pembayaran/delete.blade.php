@extends('layouts.form')
@section('content')
    <h5 class="mt-5">{{ $title }} {{ $primary->id_pembayaran }}?</h5>
    <p>Tindakan ini tidak dapat dibatalkan.</p>
    <form action="{{ route(str($resource) . '.destroy', ['id' => $primary->id_pembayaran ]) }}" method="post" class="mt-5">
        @method('delete')
        @csrf
        <div class="d-flex justify-content-end mt-3">
            <button class="btn border-0 btn-outline-danger" type="submit"><i class="bi-trash"></i><span class="ms-2">Delete</span></button>
        </div>
    </form>
@endsection
