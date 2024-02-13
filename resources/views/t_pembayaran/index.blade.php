@extends('layouts.dashboard')
@section('topnav')
@include('components.preferences-button')
@endsection
@section('bottomnav')
@endsection
@section('content')
@if ($primary->count() == 0)
@include('components.no-data-text')
@else
<div class="rounded border border-bottom-0 table-responsive">
    <table class="m-0 table table-hover align-middle">
        <tr>
            <th>#</th>
            <th>NIS</th>
            <th>Nama</th>
            <th>Jenis kelamin</th>
            <th>Kelas</th>
            <th>Nama orang tua</th>
            <th>Biaya SPP</th>
            <th>Jatuh tempo</th>
        </tr>
        @foreach ($primary as $row)
        <tr id="row{{ $loop->index + 1 }}" style="cursor: pointer">
            <td>{{ $primary->perPage() * ($primary->currentPage() - 1) + $loop->index + 1 }}</td>
            <td>{{ $row->nis }}</td>
            <td>{{ $row->nama_siswa }}</td>
            <td>{{ $row->jk ? 'Laki-laki' : 'Perempuan' }}</td>
            <td>{{ $row->kelas->nm_kelas }}</td>
            <td>{{ $row->nama_orang_tua }}</td>
            <td>Rp. {{ (int) $row->spp_perbulan }}</td>
            <td class="text-{{ $row->debt ? 'danger' : 'success' }}">{{ $row->debt ? 'Ya' : 'Tidak' }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endif
@endsection
