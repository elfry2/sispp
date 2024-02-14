@extends('layouts.form')
@section('content')
<form action="{{ route($resource . '.create') }}" method="get">
    <div class="form-floating mt-3">
        <input name="nis" type="text" id="nisTextInput" class="form-control" placeholder="" value="{{ old('nis') ?? request('nis') }}" autofocus autocomplete="off">
        <label for="nameTextInput">NIS / Nama</label>
    </div>
    <div id="studentSuggestions"></div>
    <script>
        const nisTextInput = document.getElementById('nisTextInput');

        const studentSuggestions = document.getElementById('studentSuggestions');

        const tahunPembayaranNumberInput = document.getElementById('tahunPembayaranNumberInput');

        const students = {!! $primary->toJson() !!};

        nisTextInput.addEventListener('keyup', function() {

            studentSuggestions.innerHTML = '';

            if(nisTextInput.value.length < 2) return;

            filteredStudents = students.filter(function(student) {

                return (student.nis + " - " + student.nama_siswa).toLowerCase()
                    .includes(nisTextInput.value.toLowerCase());
            });

            filteredStudents.forEach(function(student) {
                studentSuggestions.innerHTML += `<a href="#"
                    class="mt-2 btn border-0 btn-outline-{{ preference('theme') === 'dark' ? 'light' : 'dark' }} d-block text-start"
                    onclick="nisTextInput.value = ${String(student.nis)}; studentSuggestions.innerHTML = ''; tahunPembayaranNumberInput.focus()"
                >${student.nis} - ${student.nama_siswa}</a>`;
            })
        })
    </script>

    <div class="form-floating mt-3">
        <input name="tahun_pembayaran" type="number" id="tahunPembayaranNumberInput" class="form-control" placeholder="" value="{{ old('tahun_pembayaran') ?? request('tahun_pembayaran') }}">
        <label for="tahunPembayaranNumberInput">Tahun pembayaran</label>
    </div>
    <div class="d-flex justify-content-end mt-3">
        <button class="btn border-0 btn-outline-{{ preference('theme') == 'dark' ? 'light' : 'dark' }}" type="submit"><i class="bi-search"></i><span class="ms-2">Cari</span></button>
    </div>
</form>
@if(isset($secondary))
<form action="{{ route("$resource.update", ['id' => request('nis')]) }}" method="post">
    @method('patch')
    @csrf
    <input type="hidden" name="tahun_pembayaran" value="{{ request('tahun_pembayaran') }}">
    <div class="row">
        @php
        $months = [
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
        ];
        @endphp

        @for($i = 0; $i < 2; $i++)
        <div class="col mt-3">
            @for($j = 0; $j < count($months) / 2; $j++)
            @php $monthIndex = $j + ($i * 6); @endphp
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="bulan[]" value="{{ $monthIndex }}" @if($secondary->search($monthIndex) !== false) checked @endif id="month{{ $monthIndex }}CheckBoxInput">
                <label class="form-check-label" for="month{{ $monthIndex }}CheckBoxInput">
                    {{ $months[$monthIndex] }}
                </label>
            </div>
            @endfor
        </div>
        @endfor
    </div>
    <div class="d-flex justify-content-end mt-3">
        <button class="btn border-0 btn-outline-{{ preference('theme') == 'dark' ? 'light' : 'dark' }}" type="submit"><i class="bi-pencil-square"></i><span class="ms-2">Simpan</span></button>
    </div>
</form>
@endif
@endsection
