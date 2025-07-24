<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Koulastic Management</title>
    <link rel="icon" type="img/png" href="{{ asset('img/logo koulastic.png') }}" <link
        href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="{{ asset('ole/css/styles.css') }}" rel="stylesheet" />
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <!-- FullCalendar CSS & JS -->
    {{-- <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
<script src="
https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.18/index.global.min.js
"></script> --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

    {{-- <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script> --}}
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark justify-content-between">
        <!-- Navbar Brand-->
        <img class="rounded-circle me-2" style="height: 100px; width: 100px; margin-left: 60px; margin-top: 20px;"
            src="{{ asset('img/logo koulastic.png') }}" alt="Logo">
        <a class="navbar-brand ms-3" href="/">Koulastic Management</a>
        <!-- Sidebar Toggle-->

        <a class="me-3 btn btn-danger d-flex align-items-center gap-1" href="{{ route('logout') }}">
            <i class='bx bx-arrow-in-left-square-half fs-5'></i>
            Logout
        </a>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Daftar Menu</div>
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">
                            <i class='bx bxs-home-alt-2 fs-5 me-2'></i>
                            {{-- <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div> --}}
                            Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('pemesanan*') ? 'active' : '' }}"
                            href="{{ route('pemesanan.index') }}">
                            <i class='bx  bxs-contact-book fs-5 me-2'></i>
                            {{-- <div class="sb-nav-link-icon"><i class="fas fa-calendar-check"></i></div> --}}
                            Pemesanan
                        </a>
                        <a class="nav-link {{ request()->routeIs('acara*') ? 'active' : '' }}"
                            href="{{ route('acara.index') }}">
                            <i class='bx  bxs-ticket fs-5 me-2'></i>
                            {{-- <div class="sb-nav-link-icon"><i class="fas fa-calendar-check"></i></div> --}}
                            Acara
                        </a>

                        @php
                            // Jika request ada rute 'transaksi-masuk' atau 'transaksi-keluar'
                            $isKeuanganActive =
                                request()->routeIs('transaksi-masuk*') || request()->routeIs('transaksi-keluar*');
                        @endphp

                        <a class="nav-link {{ $isKeuanganActive ? 'active' : 'collapsed' }}" href="#"
                            data-bs-toggle="collapse" data-bs-target="#collapsePages"
                            aria-expanded="{{ $isKeuanganActive ? 'true' : 'false' }}" aria-controls="collapsePages">
                            {{-- <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div> --}}
                            <i class='bx  bxs-wallet-note fs-5 me-2'></i>
                            Keuangan
                            <i class='bx  bxs-chevron-{{ $isKeuanganActive ? 'up' : 'down' }} fs-5 ms-auto'></i>
                        </a>
                        <div class="collapse {{ $isKeuanganActive ? 'show' : '' }}" id="collapsePages"
                            aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link {{ request()->routeIs('transaksi-masuk*') ? 'active' : '' }}"
                                    href="{{ route('transaksi-masuk.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-arrow-circle-down"></i></div>
                                    Transaksi Masuk
                                </a>
                                <a class="nav-link {{ request()->routeIs('transaksi-keluar*') ? 'active' : '' }}"
                                    href="{{ route('transaksi-keluar.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-dollar-sign"></i></div>
                                    Transaksi Keluar
                                </a>
                            </nav>
                        </div>

                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Koulastic Manajemen
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                @yield('content')
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('ole/js/scripts.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('ole/assets/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('ole/assets/demo/chart-bar-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="{{ asset('ole/js/datatables-simple-demo.js') }}"></script>
    @yield('scripts')
</body>

</html>
