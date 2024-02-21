@extends('layouts.dashboard')
@section('topnav')
@include('components.preferences-button')
@include('components.unfiltered-report-generation-button')
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
                    <th>Nama</th>
                    <th>Jumlah siswa</th>
                    <th>Jumlah siswa maksimal</th>
                </tr>
                @foreach ($primary as $row)
                <tr id="row{{ $loop->index + 1 }}" style="cursor: pointer" onclick="window.location.href = '{{ route("$resource.edit", ['id' => $row->kd_kls]) . '?back=' . route($resource . '.index') }}'">
                        <td>{{ $primary->perPage() * ($primary->currentPage() - 1) + $loop->index + 1 }}</td>
                        <td>{{ $row->nm_kelas }}</td>
                        <td>{{ $row->siswa->count() }}</td>
                        <td>{{ $row->jumlah_siswa }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endif
@endsection
