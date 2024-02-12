@extends('layouts.form')
@section('content')
<form action="{{ route($resource . '.store') }}" method="post">
    @csrf

    <div class="form-floating mt-3">
        <input name="nis" type="text" id="nisTextInput" class="form-control" placeholder="" value="{{ old('nis') }}" autofocus>
        <label for="nameTextInput">NIS / Nama</label>
    </div>
    <div class="form-floating mt-3">
        <input name="tahun_pembayaran" type="number" id="tahunPembayaranNumberInput" class="form-control" placeholder="" value="{{ old('tahun_pembayaran') }}">
        <label for="tahunPembayaranNumberInput">Tahun pembayaran</label>
    </div>
    @if(Route::is('t_pembayaran.create.check'))
    <div class="row">
        {{ $semesters = (object) [
            array_slice($months, 0, 5),
            array_slice($months, 6, 11)
        ]}}

        @foreach($semesters as $semester)
        <div class="col">
            @foreach($semester as $month)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" @if($month->hasPaid) checked @endif>
                <label class="form-check-label" for="flexCheckDefault">
                    $month->name
                </label>
            </div>
            @endforeach
        </div>
        @enforeach
    </div>
    @endif
    <div class="d-flex justify-content-end mt-3">
        <button class="btn border-0 btn-outline-{{ preference('theme') == 'dark' ? 'light' : 'dark' }}" type="submit"><i class="bi-pencil-square"></i><span class="ms-2">Tambah</span></button>
    </div>
</form>
@endsection
