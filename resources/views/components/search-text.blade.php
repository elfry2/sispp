@if (!empty(request('q')))
        <p>Menampilkan {{ Str::lower($title) }} seperti "{{ request('q') }}". <a href="{{ url()->current() }}">Kosongkan</a></p>
    @endif
