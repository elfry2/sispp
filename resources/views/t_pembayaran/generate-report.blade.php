@extends('layouts.form')
@section('content')
<form action="{{ route($resource . '.generateReport') }}" method="post">
    @csrf
    <div class="form-floating mt-3">
        <select class="form-select" name="kelas" id="classSelectInput">
            <option value="" selected>Semua</option>
            @foreach($primary as $option)
                <option value="{{ $option->kd_kls }}">{{ $option->nm_kelas }}</option>
            @endforeach
        </select>
        <label for="classSelectInput">Kelas</label>
    </div>
    <div class="form-floating mt-3">
        <select class="form-select mt-3" name="tahun_pembayaran" id="yearSelectInput">
            <option value="" selected>Semua</option>
            @foreach($secondary as $option)
                <option value="{{ $option }}">{{ $option }}</option>
            @endforeach
        </select>
        <label for="yearSelectInput">Tahun</label>
    </div>
    <div class="form-floating mt-3">
        <select class="form-select mt-3" name="bulan" id="monthSelectInput">
            <option value="" selected>Semua</option>
            @foreach([
                'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            ] as $option)
                <option value="{{ $loop->index }}">{{ $option }}</option>
            @endforeach
        </select>
        <label for="monthSelectInput">Bulan</label>
    </div>
    <div class="d-flex justify-content-end mt-3">
        <button class="btn border-0 btn-outline-{{ preference('theme') == 'dark' ? 'light' : 'dark' }}" type="submit"><i class="bi-send"></i><span class="ms-2">Buat</span></button>
    </div>
</form>
@endsection
