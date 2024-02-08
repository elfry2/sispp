@extends('layouts.form')
@section('content')
<form action="{{ route($resource . '.update', ['id' => $primary->nis]) . '?back=' . route($resource . '.index') }}" method="post">
    @method('patch')
    @csrf
    <div class="form-floating mt-3">
        <input name="nis" type="text" id="nisTextInput" class="form-control" placeholder="" value="{{ old('nis') ?? $primary->nis }}" autofocus>
        <label for="nameTextInput">NIS</label>
    </div>
    <div class="form-floating mt-3">
        <input name="nama_siswa" type="text" id="nameTextInput" class="form-control" placeholder="" value="{{ old('nama_siswa') ?? $primary->nama_siswa }}">
        <label for="nameTextInput">Nama</label>
    </div>
    <div class="form-floating mt-3">
        <input name="alamat" type="text" id="addressTextInput" class="form-control" placeholder="" value="{{ old('alamat') ?? $primary->alamat }}">
        <label for="nameTextInput">Alamat</label>
    </div>
    <div class="form-floating mt-3">
        <input name="tgl_lahir" type="date" id="birthDateInput" class="form-control" placeholder="" value="{{ old('tgl_lahir') ?? $primary->tgl_lahir }}">
        <label for="nameTextInput">Tanggal lahir</label>
    </div>
    <div class="form-floating mt-3">
        <input name="tempat_lahir" type="text" id="birthPlaceTextInput" class="form-control" placeholder="" value="{{ old('tempat_lahir') ?? $primary->tempat_lahir }}">
        <label for="nameTextInput">Tempat lahir</label>
    </div>
    <div class="mt-3">
        <p>Jenis kelamin</p>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="jk" value="1" id="maleGenderRadioInput" @if($primary->jk || old('jk')) checked @endif>
            <label class="form-check-label" for="maleGenderRadioInput">
                Laki-laki
            </label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="jk" value="0" id="femaleGenderRadioInput" @if(!$primary->jk || old('jk') === "0") checked @endif>
            <label class="form-check-label" for="femaleGenderRadioInput">
                Perempuan
            </label>
        </div>
    </div>
    <div class="form-floating mt-3">
        <input name="nama_orang_tua" type="text" id="parentNameTextInput" class="form-control" placeholder="" value="{{ old('nama_orang_tua') ?? $primary->nama_orang_tua }}">
        <label for="parentNameTextInput">Nama orang tua</label>
    </div>
    <div class="form-floating mt-3">
        <input name="no_hp" type="text" id="handphoneNumberTextInput" class="form-control" placeholder="" value="{{ old('no_hp') ?? $primary->no_hp }}">
        <label for="handphoneNumberTextInput">No. handphone</label>
    </div>
    <div class="form-floating mt-3">
        <select name="kd_kls" class="form-select" id="classSelectInput">
            <option>-- Pilih kelas --</option>
            @foreach($secondary as $option)
            <option value="{{ $option->kd_kls }}" @if($primary->kd_kls === $option->kd_kls) selected @endif>{{ $option->nm_kelas }}</option>
            @endforeach
        </select>
        <label for="classSelectInput">Kelas</label>
    </div>
    <div class="input-group mt-3">
       <span class="input-group-text">Rp.</span>
       <div class="form-floating">
           <input name="spp_perbulan" type="number" min="0" step="500" id="monthlyFeeNumberInput" class="form-control" placeholder="" value="{{ old('spp_perbulan') >> $primary->spp_perbulan }}">
           <label for="monthlyFeeNumberInput">SPP per bulan</label>
       </div>
    </div>
    <div class="d-flex justify-content-end mt-3">
        <a href="{{ route($resource . '.delete', ['id' => $primary->kd_kls]) }}"
           class="btn border-0 btn-outline-danger" title="Delete {{ str($resource)->singular() }}"><i class="bi-trash"></i></a>
        <button class="btn border-0 btn-outline-{{ preference('theme') == 'dark' ? 'light' : 'dark' }}" type="submit"><i class="bi-pencil-square"></i><span class="ms-2">Save</span></button>
    </div>
</form>
@endsection
