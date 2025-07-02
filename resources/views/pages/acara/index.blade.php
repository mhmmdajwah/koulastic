@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        {{-- JUDUL --}}
        <h1 class="mt-4">Daftar Acara</h1>

        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-table me-1"></i> Daftar Acara</span>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                {{-- TABEL --}}
                <table id="datatablesSimple" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Acara</th>
                            <th>Nama Pemesan</th>
                            <th>Lokasi</th>
                            <th>Harga</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftarAcara as $acara)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $acara->nama_acara }}</td>
                                <td>
                                    <a
                                        href="{{ route('pemesanan.show', $acara->pemesanan->id) }}">{{ $acara->pemesanan->nama_pemesan }}</a>
                                </td>
                                <td>{{ $acara->lokasi }}</td>
                                <td>Rp. {{ number_format($acara->harga, 0, ',', '.') }}</td>
                                <td>{{ Carbon\Carbon::parse($acara->tanggal_mulai)->format('d M Y') }}</td>
                                <td>{{ Carbon\Carbon::parse($acara->tanggal_selesai)->format('d M Y') }}</td>
                                <td>{{ $acara->catatan ?? '-' }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('acara.show', $acara->id) }}"
                                            class="btn btn-sm btn-primary d-flex align-items-center gap-1">
                                            <i class='bx  bxs-info-circle'></i>
                                            Detail
                                        </a>
                                        <a href="{{ route('acara.edit', $acara->id) }}"
                                            class="btn btn-sm btn-warning d-flex align-items-center gap-1">
                                            <i class='bx  bxs-pencil'></i>
                                            Edit
                                        </a>
                                        {{-- Tampilkan tombol Hapus hanya jika tidak ada relasi pemesanan --}}
                                        <form
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus acara {{ $acara->nama_acara }}?');"
                                            action="{{ route('acara.destroy', $acara->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="aksi" value="hapus">
                                            <button type="submit"
                                                class="btn btn-sm btn-danger d-flex align-items-center gap-1">
                                                <i class='bx bxs-trash'></i>
                                                Hapus
                                            </button>
                                        </form>
                                        <form
                                            onsubmit="return confirm('Apakah Anda yakin ingin mengubah status acara {{ $acara->nama_acara }}?');"
                                            action="{{ route('acara.destroy', $acara->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            @if ($acara->status)
                                                <input type="hidden" name="aksi" value="nonaktifkan">
                                                <button type="submit"
                                                    class="btn btn-sm btn-danger d-flex align-items-center gap-1">
                                                    <i class='bxr  bxs-eye-slash'></i>
                                                    Nonaktifkan
                                                </button>
                                            @else
                                                <input type="hidden" name="aksi" value="aktifkan">
                                                <button type="submit"
                                                    class="btn btn-sm btn-success d-flex align-items-center gap-1">
                                                    <i class='bx  bxs-eye-alt'></i>
                                                    Aktifkan
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- END TABEL --}}
            </div>
        </div>
    </div>
@endsection
