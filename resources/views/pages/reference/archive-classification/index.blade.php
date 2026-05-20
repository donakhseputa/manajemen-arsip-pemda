@extends('layout.main')

@section('content')
    <x-breadcrumb
        :values="[__('menu.reference.menu'), __('menu.reference.archive-classifications')]">
        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#createModal">
            {{ __('menu.general.create') }}
        </button>
    </x-breadcrumb>

    <div class="card">
        <div class="card-datatable text-nowrap">
            <div class="table-responsive text-nowrap">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('sneat/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    {{ $dataTable->scripts() }}
@endpush
