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

    <div class="card mb-5">
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('model.archive_classification.code') }}</th>
                        <th>{{ __('model.archive_classification.name') }}</th>
                        <th>{{ __('model.archive_classification.description') }}</th>
                        <th>{{ __('menu.general.action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
