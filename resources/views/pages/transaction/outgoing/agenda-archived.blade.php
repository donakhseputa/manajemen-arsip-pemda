@extends('layout.main')

@section('content')
    <x-breadcrumb
        :values="[__('menu.agenda.menu'), __('menu.agenda.outgoing_letter')]">
    </x-breadcrumb>

    <div class="nav-align-top">
        <ul class="nav nav-pills mb-4">
            <li class="nav-item">
                <a href="{{ route('agenda.outgoing') }}" class="nav-link">
                    <i class="fa-solid fa-inbox me-2"></i>
                    Agenda Surat Keluar
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('agenda.outgoing.archived') }}" class="nav-link active">
                    <i class="fa-solid fa-archive me-2"></i>
                    List Arsip
                </a>
            </li>
        </ul>
    </div>

    @if(!$hasSelectedYear)
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-1">Folder Arsip Surat Keluar</h5>
                    <small class="text-muted">Pilih tahun untuk melihat isi arsip surat keluar.</small>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('agenda.outgoing.archived') }}" method="GET" class="mb-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-xl-4 col-lg-4 col-md-12">
                            <label for="search_folder_outgoing" class="form-label mb-1 text-uppercase">Pencarian</label>
                            <input type="text" class="form-control" id="search_folder_outgoing" name="search"
                                   value="{{ $search ?? '' }}"
                                   placeholder="Cari nomor agenda, nomor surat, atau penerima">
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-6">
                            <label for="start_year_folder_outgoing" class="form-label mb-1 text-uppercase">Tahun Awal</label>
                            <select class="form-select" id="start_year_folder_outgoing" name="start_year">
                                <option value="">Pilih Tahun</option>
                                @foreach($archiveYearOptions as $year)
                                    <option value="{{ $year }}" @selected((int) $folderStartYear === (int) $year)>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-6">
                            <label for="end_year_folder_outgoing" class="form-label mb-1 text-uppercase">Tahun Akhir</label>
                            <select class="form-select" id="end_year_folder_outgoing" name="end_year">
                                <option value="">Pilih Tahun</option>
                                @foreach($archiveYearOptions as $year)
                                    <option value="{{ $year }}" @selected((int) $folderEndYear === (int) $year)>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12">
                            <label class="form-label mb-1 text-uppercase d-block">Aksi</label>
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa-solid fa-filter me-1"></i> Filter
                                </button>
                                <a href="{{ route('agenda.outgoing.archived') }}" class="btn btn-secondary">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

                @if($archiveYears->count())
                    <div class="row g-3">
                        @foreach($archiveYears as $folder)
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <a href="{{ route('agenda.outgoing.archived', ['year' => $folder->archive_year]) }}"
                                   class="text-decoration-none">
                                    <div class="card border h-100 shadow-sm">
                                        <div class="card-body d-flex align-items-center gap-3">
                                            <div class="avatar flex-shrink-0">
                                                <span class="avatar-initial rounded bg-label-primary">
                                                    <i class="fa-solid fa-folder"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Arsip Tahun {{ $folder->archive_year }}</h6>
                                                <small class="text-muted">{{ $folder->total }} surat keluar</small>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        Tidak ada folder arsip yang sesuai dengan filter.
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="card mb-5">
            <div class="card-header border-bottom">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <div>
                        <h5 class="mb-1">Isi Folder Arsip Tahun {{ $selectedYear }}</h5>
                        <small class="text-muted">Daftar arsip surat keluar pada tahun {{ $selectedYear }}.</small>
                    </div>
                    <a href="{{ route('agenda.outgoing.archived') }}" class="btn btn-sm btn-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Folder
                    </a>
                </div>
                <form action="{{ url()->current() }}" method="GET">
                    <input type="hidden" name="year" value="{{ $selectedYear }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-xl-2 col-lg-2 col-md-6">
                            <label for="per_page" class="form-label mb-1 text-uppercase">Per Halaman</label>
                            <select class="form-select" id="per_page" name="per_page">
                                @foreach([10, 25, 50, 100] as $size)
                                    <option value="{{ $size }}" @selected((int) $perPage === $size)>{{ $size }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-6">
                            <label for="since" class="form-label mb-1 text-uppercase">Dari Tanggal</label>
                            <input type="date" class="form-control" id="since" name="since" value="{{ $since }}">
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-6">
                            <label for="until" class="form-label mb-1 text-uppercase">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="until" name="until" value="{{ $until }}">
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-6">
                            <label for="filter" class="form-label mb-1 text-uppercase">Filter</label>
                            <select class="form-select" id="filter" name="filter">
                                <option value="letter_date" @selected($filter === 'letter_date')>Tanggal Surat</option>
                            </select>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12">
                            <label class="form-label mb-1 text-uppercase d-block">Aksi</label>
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa-solid fa-filter me-1"></i> Filter
                                </button>
                                <a href="{{ route('agenda.outgoing.print') . '?' . $query }}"
                                   target="_blank"
                                   class="btn btn-primary">
                                    <i class="fa-solid fa-print me-1"></i> Cetak
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ __('model.letter.agenda_number') }}</th>
                        <th>{{ __('model.letter.reference_number') }}</th>
                        <th>{{ __('model.letter.to') }}</th>
                        <th>{{ __('model.letter.letter_date') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($data as $agenda)
                        <tr>
                            <td>
                                <i class="fab fa-angular fa-lg text-danger me-3"></i>
                                <a href="{{ route('agenda.outgoing.archived.show', $agenda->id) }}" class="fw-semibold">
                                    {{ $agenda->agenda_number }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('agenda.outgoing.archived.show', $agenda->id) }}">{{ $agenda->reference_number }}</a>
                            </td>
                            <td>{{ $agenda->to }}</td>
                            <td>{{ $agenda->formatted_letter_date }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                {{ __('menu.general.empty') }}
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {!! $data->appends([
            'year' => $selectedYear,
            'per_page' => $perPage,
            'since' => $since,
            'until' => $until,
            'filter' => $filter,
        ])->links() !!}
    @endif
@endsection
