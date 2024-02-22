<form method="post" action="{{ route($resource . '.generateReport') }}">
    @csrf
    <button type="submit" class="btn border-0 btn-outline-{{ preference('theme') == 'dark' ? 'light' : 'dark' }} ms-2" title="Buat laporan {{ str($title)->lower() }}"><i class="bi-file-earmark-spreadsheet"></i></button>
</form>
