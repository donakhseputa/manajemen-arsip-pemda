@extends('layout.main')

@section('content')
    <x-breadcrumb
        :values="[__('menu.agenda.menu'), __('menu.agenda.incoming_letter')]">
    </x-breadcrumb>

    <div class="nav-align-top">
        <ul class="nav nav-pills mb-4">
            <li class="nav-item">
                <button type="button" class="nav-link active">
                    <i class="fa-solid fa-inbox me-2"></i>
                    Agenda Surat Masuk
                </button>
            </li>
            <li class="nav-item">
                <a href="{{ route('agenda.incoming.archived') }}" class="nav-link">
                    <i class="fa-solid fa-archive me-2"></i>
                    List Arsip
                </a>
            </li>
        </ul>
    </div>

    <div class="card mb-5">
        <div class="card-header">
            <form action="{{ url()->current() }}">
                <input type="hidden" name="search" value="{{ $search ?? '' }}">
                <div class="row g-2">
                    
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="mb-3">
                            <label for="per_page" class="form-label">{{ __('menu.general.per_page') }}</label>
                            <select class="form-select" name="per_page" onchange="this.form.submit()">
                                <option value="10" @selected(old('per_page', $perPage) == 10)>10</option>
                                <option value="25" @selected(old('per_page', $perPage) == 25)>25</option>
                                <option value="50" @selected(old('per_page', $perPage) == 50)>50</option>
                                <option value="100" @selected(old('per_page', $perPage) == 100)>100</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="mb-3">
                            <x-input-form name="since" :label="__('menu.agenda.start_date')" type="date"
                                          :value="$since ? date('Y-m-d', strtotime($since)) : ''"/>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="mb-3">
                            <x-input-form name="until" :label="__('menu.agenda.end_date')" type="date"
                                          :value="$until ? date('Y-m-d', strtotime($until)) : ''"/>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="mb-3">
                            <label for="filter" class="form-label">{{ __('menu.agenda.filter_by') }}</label>
                            <select class="form-select" id="filter" name="filter">
                                <option
                                    value="letter_date" @selected(old('filter', $filter) == 'letter_date')>{{ __('model.letter.letter_date') }}</option>
                                <option
                                    value="received_date" @selected(old('filter', $filter) == 'received_date')>{{ __('model.letter.received_date') }}</option>
                                <option
                                    value="created_at" @selected(old('filter', $filter) == 'created_at')>{{ __('model.general.created_at') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label class="form-label d-block">{{ __('menu.general.action') }}</label>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-primary" type="submit">
                                    <i class="fa-solid fa-filter me-1"></i> {{ __('menu.general.filter') }}
                                </button>
                                <a href="{{ route('agenda.incoming.print') . '?' . $query }}"
                                   target="_blank"
                                   class="btn btn-sm btn-primary">
                                    <i class="fa-solid fa-print me-1"></i> {{ __('menu.general.print') }}
                                </a>
                                <button class="btn btn-sm btn-danger btn-archive" type="button">
                                    <i class="fa-solid fa-box-archive me-1"></i> {{ __('menu.general.archive') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" name="select_all" id="select_all">
                        </th>
                        <th>{{ __('model.letter.agenda_number') }}</th>
                        <th>{{ __('model.letter.reference_number') }}</th>
                        <th>{{ __('model.letter.from') }}</th>
                        <th>{{ __('model.letter.received_date') }}</th>
                    </tr>
                </thead>
                @if($data)
                    <tbody>
                        @foreach($data as $agenda)
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected[]" value="{{ $agenda->id }}">
                                </td>
                                <td><i class="fab fa-angular fa-lg text-danger me-3"></i>
                                    <strong>{{ $agenda->agenda_number }}</strong></td>
                                <td>
                                    <a href="{{ route('transaction.incoming.show', $agenda) }}">{{ $agenda->reference_number }}</a>
                                </td>
                                <td>{{ $agenda->from }}</td>
                                <td>{{ $agenda->formatted_received_date }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                @else
                    <tbody>
                    <tr>
                        <td colspan="5" class="text-center">
                            {{ __('menu.general.empty') }}
                        </td>
                    </tr>
                    </tbody>
                @endif
            </table>
        </div>
    </div>

    {!! $data->appends(['search' => $search, 'since' => $since, 'until' => $until, 'filter' => $filter])->links() !!}
@endsection

@push('script')
    <script>
        globalThis.AgendaIncomingJS = (function ($) {
            "use strict";

            var pub = {
                initModule: function (module) {
                    if (module.isActive !== undefined && !module.isActive) {
                        return;
                    }
                    if ($.isFunction(module.init)) {
                        module.init();
                    }
                    $.each(module, function () {
                        if ($.isPlainObject(this)) {
                            pub.initModule(this);
                        }
                    });
                },

                init: function () {
                    console.log("Agenda Incoming JS Initialized");
                    initCheckboxSelection();
                }
            };

            function initCheckboxSelection() {
                $(document).on("change", "#select_all", function () {
                    var isChecked = $(this).is(":checked");
                    $('input[name="selected[]"]').prop("checked", isChecked);
                });

                $(document).on("change", 'input[name="selected[]"]', function () {
                    var allChecked = $('input[name="selected[]"]').length === $('input[name="selected[]"]:checked').length;
                    $('#select_all').prop("checked", allChecked);
                });

                $(document).on("click", ".btn-archive", function () {
                    var selectedIds = $('input[name="selected[]"]:checked').map(function () {
                        return $(this).val();
                    }).get();

                    if (selectedIds.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: '{{ __('menu.general.no_selection') }}',
                            text: '{{ __('menu.general.select_at_least_one') }}'
                        });
                        return;
                    }

                    Swal.fire({
                        title: '{{ __('menu.general.archive_confirm') }}',
                        text: "{{ __('menu.general.archive_warning') }}",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#696cff',
                        confirmButtonText: '{{ __('menu.general.archive') }}',
                        cancelButtonText: '{{ __('menu.general.cancel') }}'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ route('agenda.archive') }}',
                                method: 'POST',
                                data: {
                                    ids: selectedIds,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function (response) {
                                    Toast.fire({
                                        icon: 'success',
                                        title: response.message
                                    });
                                    // location.reload();
                                },
                                error: function (xhr) {
                                    Toast.fire({
                                        icon: 'error',
                                        title: xhr.responseJSON.message || '{{ __('menu.general.error_occurred') }}'
                                    });
                                }
                            });
                        }
                    });
                });
            }

            return pub;
        }(jQuery));

        $(document).ready(function () {
            AgendaIncomingJS.initModule(AgendaIncomingJS);
        });
    </script>
@endpush