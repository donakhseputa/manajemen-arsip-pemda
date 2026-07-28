@extends('layout.main')

@push('style')
    <style>
        .archive-classification-card {
            overflow: hidden;
        }

        .archive-classification-table-scroll {
            width: 100%;
            overflow-x: auto;
        }

        #archiveclassifications-table {
            margin-bottom: 0 !important;
        }

        #archiveclassifications-table thead th {
            font-size: 0.78rem;
            letter-spacing: .03em;
            color: #697a8d;
            text-transform: uppercase;
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.25rem;
            white-space: nowrap;
        }

        #archiveclassifications-table tbody td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
            border-color: #edf0f4;
        }

        #archiveclassifications-table_wrapper {
            width: 100%;
        }

        #archiveclassifications-table_wrapper .dataTables_info {
            color: #697a8d;
            font-size: .875rem;
            padding-top: 0;
            white-space: nowrap;
        }

        #archiveclassifications-table_wrapper .archive-classification-footer {
            width: 100%;
            margin: 0;
        }

        #archiveclassifications-table_wrapper .archive-classification-pagination {
            margin-left: auto;
        }

        #archiveclassifications-table_wrapper .dataTables_paginate,
        #archiveclassifications-table_wrapper .dataTables_paginate .pagination {
            display: flex;
            justify-content: flex-end !important;
            width: auto;
            margin: 0;
        }

        @media (max-width: 767.98px) {
            #archiveclassifications-table_wrapper .archive-classification-pagination {
                margin-left: 0;
                width: 100%;
            }

            #archiveclassifications-table_wrapper .dataTables_paginate,
            #archiveclassifications-table_wrapper .dataTables_paginate .pagination {
                justify-content: flex-start !important;
                flex-wrap: wrap;
            }
        }
    </style>
@endpush

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

    <div class="card archive-classification-card">
        {{ $dataTable->table() }}
    </div>
@endsection

@push('script')
    <script src="{{ asset('sneat/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    {{ $dataTable->scripts() }}
    <script>
        $(function () {
            const table = $('#archiveclassifications-table');

            if (table.length && !table.parent().hasClass('archive-classification-table-scroll')) {
                table.wrap('<div class="table-responsive text-nowrap archive-classification-table-scroll"></div>');
            }
        });
    </script>
@endpush
