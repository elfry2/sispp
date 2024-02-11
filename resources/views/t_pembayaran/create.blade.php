@extends('layouts.form')
@section('content')
<form action="{{ route($resource . '.store') }}" method="post">
    @csrf

    <!-- $table->string('nis')->unique(); -->
    <!-- $table->primary('nis'); -->
    <!-- $table->string('nama_siswa', 30); -->
    <!-- $table->string('alamat', 40); -->
    <!-- $table->date('tgl_lahir'); -->
    <!-- $table->string('tempat_lahir', 30); -->
    <!-- $table->string('jk', 15); -->
    <!-- $table->string('nama_orang_tua', 15); -->
    <!-- $table->string('no_hp'); -->
    <!-- $table->unsignedBigInteger('kd_kls'); -->
    <!-- $table->foreign('kd_kls')->references('kd_kls')->on('t_kelas'); -->
    <!-- $table->decimal('spp_perbulan'); -->

    <div class="form-floating mt-3">
        <input name="nis" type="text" id="nisTextInput" class="form-control" placeholder="" value="{{ old('nis') }}" autofocus>
        <label for="nameTextInput">NIS</label>
    </div>
    <div class="form-floating mt-3">
        <input name="nama_siswa" type="text" id="nameTextInput" class="form-control" placeholder="" value="{{ old('nama_siswa') }}">
        <label for="nameTextInput">Nama</label>
    </div>
    <div class="form-floating mt-3">
        <input name="alamat" type="text" id="addressTextInput" class="form-control" placeholder="" value="{{ old('alamat') }}">
        <label for="nameTextInput">Alamat</label>
    </div>
    <div class="form-floating mt-3">
        <input name="tgl_lahir" type="date" id="birthDateInput" class="form-control" placeholder="" value="{{ old('tgl_lahir') }}">
        <label for="nameTextInput">Tanggal lahir</label>
    </div>
    <div class="form-floating mt-3">
        <input name="tempat_lahir" type="text" id="birthPlaceTextInput" class="form-control" placeholder="" value="{{ old('tempat_lahir') }}">
        <label for="nameTextInput">Tempat lahir</label>
    </div>
    <div class="mt-3">
        <p>Jenis kelamin</p>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="jk" value="1" id="maleGenderRadioInput" @if(old('jk') === null || old('jk')) checked @endif>
            <label class="form-check-label" for="maleGenderRadioInput">
                Laki-laki
            </label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="jk" value="0" id="femaleGenderRadioInput" @if(old('jk') === "0") checked @endif>
            <label class="form-check-label" for="femaleGenderRadioInput">
                Perempuan
            </label>
        </div>
    </div>
    <div class="form-floating mt-3">
        <input name="nama_orang_tua" type="text" id="parentNameTextInput" class="form-control" placeholder="" value="{{ old('nama_orang_tua') }}">
        <label for="parentNameTextInput">Nama orang tua</label>
    </div>
    <div class="form-floating mt-3">
        <input name="no_hp" type="text" id="handphoneNumberTextInput" class="form-control" placeholder="" value="{{ old('no_hp') }}">
        <label for="handphoneNumberTextInput">No. handphone</label>
    </div>
    <div class="form-floating mt-3">
        <select name="kd_kls" class="form-select" id="classSelectInput">
            <option selected>-- Pilih kelas --</option>
            @foreach($primary as $option)
            <option value="{{ $option->kd_kls }}" @if(old('kd_kls') == $option->kd_kls) selected @endif>{{ $option->nm_kelas }}</option>
            @endforeach
        </select>
        <label for="classSelectInput">Kelas</label>
    </div>
    <div class="input-group mt-3">
       <span class="input-group-text">Rp.</span>
       <div class="form-floating">
           <input name="spp_perbulan" type="number" min="0" step="500" id="monthlyFeeNumberInput" class="form-control" placeholder="" value="{{ old('spp_perbulan') }}">
           <label for="monthlyFeeNumberInput">SPP per bulan</label>
       </div>
    </div>
    <div class="d-flex justify-content-end mt-3">
        <button class="btn border-0 btn-outline-{{ preference('theme') == 'dark' ? 'light' : 'dark' }}" type="submit"><i class="bi-pencil-square"></i><span class="ms-2">Tambah</span></button>
    </div>
</form>
@endsection
