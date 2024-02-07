@extends('layouts.form')
@section('content')
<form action="{{ route($resource . '.update', [$resource => $primary->kd_kls]) . '?back=' . route($resource . '.index') }}" method="post">
    @method('patch')
    @csrf
    <div class="form-floating mt-3">
        <input name="nm_kelas" type="text" id="nameTextInput" class="form-control" placeholder=""
                                                                                   value="{{ old('nm_kelas') ?? $primary->nm_kelas }}" autofocus>
        <label for="nameTextInput">Nama</label>
    </div>
    <div class="form-floating mt-3">
        <input name="jumlah_siswa" type="number" id="maxStudentCountNumberInput" class="form-control" placeholder=""
                                                                                                      value="{{ old('jumlah_siswa') ?? $primary->jumlah_siswa }}" autofocus>
        <label for="maxStudentCountNumberInput">Jumlah siswa maksimal</label>
    </div>
    <div class="d-flex justify-content-end mt-3">
        <a href="{{ route($resource . '.delete', [$resource => $primary->kd_kls]) }}"
           class="btn border-0 btn-outline-danger" title="Delete {{ str($resource)->singular() }}"><i class="bi-trash"></i></a>
        <button class="btn border-0 btn-outline-{{ preference('theme') == 'dark' ? 'light' : 'dark' }}" type="submit"><i class="bi-pencil-square"></i><span class="ms-2">Save</span></button>
    </div>
</form>
@endsection
