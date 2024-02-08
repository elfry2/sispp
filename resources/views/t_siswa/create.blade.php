@extends('layouts.form')
@section('content')
<form action="{{ route($resource . '.store') }}" method="post">
        @csrf
        <div class="form-floating mt-3">
            <input name="nm_kelas" type="text" id="nameTextInput" class="form-control" placeholder=""
                value="{{ old('nm_kelas') }}" autofocus>
            <label for="nameTextInput">Nama</label>
        </div>
        <div class="form-floating mt-3">
            <input name="jumlah_siswa" type="number" id="maxStudentCountNumberInput" class="form-control" placeholder=""
                value="{{ old('jumlah_siswa') }}" autofocus>
            <label for="maxStudentCountNumberInput">Jumlah siswa maksimal</label>
        </div>
        <div class="d-flex justify-content-end mt-3">
            <button class="btn border-0 btn-outline-{{ preference('theme') == 'dark' ? 'light' : 'dark' }}" type="submit"><i class="bi-pencil-square"></i><span class="ms-2">Tambah</span></button>
        </div>
    </form>
@endsection
