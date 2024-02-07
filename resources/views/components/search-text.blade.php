@if (!empty(request('q')))
        <p>Menampilkan {{ Str::lower($title) }} seperti "{{ request('q') }}". <a href="{{ url()->current() }}">Clear</a></p>
    @endif
