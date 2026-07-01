@extends('layout.main')

@section('content')
    <x-breadcrumb
        :values="[__('menu.agenda.menu'), __('menu.agenda.incoming_letter')]">
    </x-breadcrumb>

    <div class="nav-align-top">
        <ul class="nav nav-pills mb-4">
            <li class="nav-item">
                <a href="{{ route('agenda.incoming') }}" class="nav-link">
                    <i class="fa-solid fa-inbox me-2"></i>
                    Agenda Surat Masuk
                </a>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link active">
                    <i class="fa-solid fa-archive me-2"></i>
                    List Arsip
                </button>
            </li>
        </ul>
    </div>

    <div class="card mb-5">
        <div class="card-header">
            <form action="{{ url()->current() }}">
                <input type="hidden" name="search" value="{{ $search ?? '' }}">
                <div class="row">
                    <div class="col">
                        <label for="per_page" class="form-label">{{ __('menu.general.per_page') }}</label>
                        <select class="form-select" name="per_page" onchange="this.form.submit()">
                            <option value="10" @selected(old('per_page', $perPage) == 10)>10</option>
                            <option value="25" @selected(old('per_page', $perPage) == 25)>25</option>
                            <option value="50" @selected(old('per_page', $perPage) == 50)>50</option>
                            <option value="100" @selected(old('per_page', $perPage) == 100)>100</option>
                        </select>
                    </div>
                    <div class="col">
                        <x-input-form name="since" :label="__('menu.agenda.start_date')" type="date"
                                      :value="$since ? date('Y-m-d', strtotime($since)) : ''"/>
                    </div>
                    <div class="col">
                        <x-input-form name="until" :label="__('menu.agenda.end_date')" type="date"
                                      :value="$until ? date('Y-m-d', strtotime($until)) : ''"/>
                    </div>
                    <div class="col">
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
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label">{{ __('menu.general.action') }}</label>
                            <div class="row">
                                <div class="col">
                                    <button class="btn btn-sm btn-primary"
                                            type="submit">{{ __('menu.general.filter') }}</button>
                                    <a
                                        href="{{ route('agenda.incoming.print') . '?' . $query }}"
                                        target="_blank"
                                        class="btn btn-sm btn-primary">
                                        {{ __('menu.general.print') }}
                                    </a>
                                </div>
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
                        <th>{{ __('model.letter.agenda_number') }}</th>
                        <th>{{ __('model.letter.reference_number') }}</th>
                        <th>{{ __('model.letter.from') }}</th>
                        <th>{{ __('model.letter.letter_date') }}</th>
                    </tr>
                </thead>
                @if($data)
                    <tbody>
                        @foreach($data as $agenda)
                            <tr>
                                <td><i class="fab fa-angular fa-lg text-danger me-3"></i>
                                    <strong>{{ $agenda->agenda_number }}</strong></td>
                                <td>
                                    <a href="{{ route('transaction.incoming.show', $agenda) }}">{{ $agenda->reference_number }}</a>
                                </td>
                                <td>{{ $agenda->from }}</td>
                                <td>{{ $agenda->formatted_letter_date }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                @else
                    <tbody>
                    <tr>
                        <td colspan="4" class="text-center">
                            {{ __('menu.general.empty') }}
                        </td>
                    </tr>
                    </tbody>
                @endif
                <tfoot class="table-border-bottom-0">
                    <tr>
                        <th scope="col">{{ __('model.letter.agenda_number') }}</th>
                        <th scope="col">{{ __('model.letter.reference_number') }}</th>
                        <th scope="col">{{ __('model.letter.from') }}</th>
                        <th scope="col">{{ __('model.letter.letter_date') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {!! $data->appends(['search' => $search, 'since' => $since, 'until' => $until, 'filter' => $filter])->links() !!}
@endsection
