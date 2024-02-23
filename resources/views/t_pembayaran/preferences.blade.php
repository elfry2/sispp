@extends('layouts.form')
@section('content')
<form action="{{ route(str($resource) . '.applyPreferences', [Str::singular($resource) => $secondary]) }}" method="post">
    @csrf
    <div class="mt-3">
        <b>Urutan</b>
        <div class="form-floating mt-3">
            <select name="order_column" class="form-select" id="orderColumnSelectInput" autofocus>
                @foreach ($primary as $option)
                <option value="{{ $option->value }}" @if (preference($resource . '.order.column') == $option->value) selected @endif>
                {{ $option->label }}</option>
                @endforeach
            </select>
            <label for="orderColumnSelectInput">Urutkan berdasarkan</label>
        </div>
        <div class="form-floating mt-3">
            <select name="order_direction" class="form-select" id="orderDirectionSelectInput">
                @foreach ([
                (object) [
                'value' => 'ASC',
                'label' => 'Ascending'
                ],
                (object) [
                'value' => 'DESC',
                'label' => 'Descending'
                ],
                ] as $option)
                <option value="{{ $option->value }}" @if (str(preference($resource . '.order.direction'))->upper() == $option->value) selected @endif>
                {{ $option->label }}
                </option>
                @endforeach
            </select>
            <label for="orderDirectionSelectInput">Arah pengurutan</label>
        </div>
    </div>

    <div class="mt-5">
        <b>Saring</b>
        <div class="form-floating mt-3">
            <select class="form-select" name="kd_kls" id="classSelectInput">
                <option value="" selected>Semua</option>
                @foreach($secondary as $option)
                <option value="{{ $option->kd_kls }}" @if (preference($resource . '.filters.classId') == $option->kd_kls) selected @endif>{{ $option->nm_kelas }}</option>
                @endforeach
            </select>
            <label for="classSelectInput">Kelas</label>
        </div>
        <div class="form-floating mt-3">
            <select class="form-select mt-3" name="tahun_pembayaran" id="yearSelectInput">
                <option value="" selected>Semua</option>
                @foreach($tertiary as $option)
                <option value="{{ $option }}" @if (preference($resource . '.filters.payments.year') == $option) selected @endif>{{ $option }}</option>
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
                <option value="{{ $loop->index }}" @if (preference($resource . '.filters.payments.month') == $loop->index) selected @endif>{{ $option }}</option>
                @endforeach
            </select>
            <label for="monthSelectInput">Bulan</label>
        </div>
        <div class="form-floating mt-3">
            <select class="form-select mt-3" name="punya_tunggakan" id="hasDebtSelectInput">
                <option value="" selected>Semua</option>
                @foreach([
                'Menunggak',
                'Tidak memiliki tunggakan',
                ] as $option)
                <option value="{{ $loop->index + 1 }}" @if (preference($resource . '.filters.hasDebt') == ($loop->index + 1) ) selected @endif>{{ $option }}</option>
                @endforeach
            </select>
            <label for="hasDebtSelectInput">Punya tunggakan</label>
        </div>

    </div>
    <div class="d-flex justify-content-end mt-3">
        <button class="btn" type="submit"><i class="bi-pencil-square"></i><span class="ms-2">Simpan</span></button>
    </div>
</form>
@endsection
