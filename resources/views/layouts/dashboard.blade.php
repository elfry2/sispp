<!doctype html>
<html lang="{{ preference('lang', 'en') }}" data-bs-theme="{{ preference('theme', 'light') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ (isset($title) ? "$title | " : '') . config('app.name') }}</title>
    <link href="/packages/bootstrap-5.3.1-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/packages/bootstrap-icons-1.11.0/bootstrap-icons.css" rel="stylesheet">
    <link href="/packages/sparksuite-simplemde-markdown-editor-6abda7a/dist/simplemde.min.css" rel="stylesheet">
    <script src="/packages/sparksuite-simplemde-markdown-editor-6abda7a/dist/simplemde.min.js"></script>
    <link href="/css/stylesheet.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-sm-2 d-{{ preference('sidenav.display') }}" id="sidenav">

                <!-- BEGIN sidenav -->

                <div class="sticky-top mt-3 bg-{{ preference('theme', 'light') == 'light' ? 'white' : 'dark' }}">
                    <div class="d-flex align-items-center">
                        <span class="hide-on-big-screens me-2">
                            @include('components.sidenav-visibility-toggle-button')
                        </span>
                        <a class="fs-5 text-{{ preference('theme', 'light') == 'light' ? 'dark' : 'light' }} fw-bold" href="{{ route('home.index') }}">{{ config('app.name') }}</a>
                        <div class="btn invisible"><i class="bi-moon"></i></div>
                    </div>
                    <div class="mt-3">
                        <b>Aplikasi</b>
                        <div class="list-group">
                            <a href="{{ route('summary.index') }}"
                                class="list-group-item list-group-item-action border-0 @if (Route::is('summary.*')) bg-body-secondary rounded @endif"><i
                                class="bi-card-text"></i><span class="ms-2">Ikhtisar</span></a>
                            <a href="{{ route('t_pembayaran.index') }}"
                                class="list-group-item list-group-item-action border-0 @if (Route::is('t_pembayaran.*')) bg-body-secondary rounded @endif"><i
                                class="bi-cash-coin"></i><span class="ms-2">Pembayaran</span></a>
                            @if (Auth::user()->level->id == 2)
                                {{-- Administrator level id --}}
                                <a href="{{ route('t_siswa.index') }}"
                                    class="list-group-item list-group-item-action border-0 @if (Route::is('t_siswa.*')) bg-body-secondary rounded @endif"><i
                                    class="bi-backpack"></i><span class="ms-2">Siswa</span></a>
                                <a href="{{ route('t_kelas.index') }}"
                                    class="list-group-item list-group-item-action border-0 @if (Route::is('t_kelas.*')) bg-body-secondary rounded @endif"><i
                                        class="bi-building"></i><span class="ms-2">Kelas</span></a>
                                <a href="{{ route('users.index') }}"
                                    class="list-group-item list-group-item-action border-0 @if (Route::is('users.*')) bg-body-secondary rounded @endif"><i
                                    class="bi-people"></i><span class="ms-2">Pengguna</span></a>
                            @endif
                            <form action="{{ route('preference.store') }}" method="post">
                                @csrf
                                {{-- <input type="hidden" name="redirectTo" value="{{ url()->current() }}"> --}}
                                <input type="hidden" name="key" value="theme">
                                <button type="submit" name="value"
                                    value="{{ preference('theme', 'light') == 'light' ? 'dark' : 'light' }}"
                                    class="list-group-item list-group-item-action border-0 rounded"><i
                                        class="bi-{{ preference('theme', 'light') == 'light' ? 'moon' : 'sun' }}"></i><span
                                        class="ms-2">{{ preference('theme', 'light') == 'light' ? 'Tema gelap' : 'Tema terang' }}</span></button>
                            </form>
                        </div>
                    </div>
                    <div class="mt-3">
                        <b>{{ Auth::user()->name }}</b>
                        <div class="list-group">
                            <a href="{{ route('profile.edit') }}"
                                class="list-group-item list-group-item-action border-0 rounded"><i
                                    class="bi-person"></i><span class="ms-2">Profil</span>
                            </a>
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit"
                                    class="list-group-item list-group-item-action border-0 rounded"><i
                                        class="bi-box-arrow-left"></i><span class="ms-2">Keluar</span></button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- END sidenav -->

            </div>
            <div class="col-sm">
                <div class="mt-3 d-flex align-items-center overflow-auto sticky-top bg-{{ preference('theme', 'light') == 'light' ? 'white' : 'dark' }}"
                    id="topnav">
                    @include('components.sidenav-visibility-toggle-button')
                    <h5 class="m-0 ms-2 me-auto">{{ $title ?? '' }}</h5>
                    <div class="ms-2"></div>
                    @if(!Route::is('summary.*'))
                        @include('components.search')
                    @endif
                    <div class="ms-2"></div>
                    @yield('topnav')
                    @if(!Route::is('summary.*'))
                        @include('components.' . (Route::is('tasks.*') ? 'task-' : '') . 'creation-button')
                        @include('components.pagination-buttons')
                    @endif
                </div>
                <div class="mt-3" id="content">
                    @include('components.messages')
                    @include('components.search-text')
                    @yield('content')

                    <div class="mt-3 d-flex align-items-center justify-content-end position-sticky overflow-auto"
                         id="bottomnav">
                        @if(!Route::is('tasks.*'))
                            @yield('bottomnav')
                        @endif
                        @if(!Route::is('summary.*'))
                            @include('components.pagination-buttons')
                        @endif
                    </div>
                    <div class="mt-2 hide-on-small-screens"></div>
                    <div class="hide-on-big-screens" style="height: 6em"></div>
                </div>
            </div>
        </div>
    </div>
    <script src="/packages/bootstrap-5.3.1-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
