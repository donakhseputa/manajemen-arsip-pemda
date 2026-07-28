@extends('layout.main')

@section('content')
    <x-breadcrumb
        :values="[__('menu.transaction.menu'), $letter->reference_number, __('menu.transaction.disposition_letter'), __('menu.general.create')]">
        <a href="{{ route('transaction.disposition.index', $letter) }}" class="btn btn-secondary">
            {{ __('menu.general.cancel') }}
        </a>
    </x-breadcrumb>

    <div class="alert alert-primary alert-dismissible" role="alert">
        {{ __('model.disposition.notice_me', ['reference_number' => $letter->reference_number]) }}
        <a href="{{ route('transaction.incoming.show', $letter) }}" class="fw-bold">
            {{ __('menu.general.view') }}
        </a>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="card mb-4">
        <div class="card-header pb-0">
            <h5 class="mb-0 fw-bold">{{ __('menu.general.create') }} {{ __('menu.transaction.disposition_letter') }}</h5>
            <small class="text-muted">
                {{ __('model.letter.reference_number') }}: {{ $letter->reference_number }}
            </small>
        </div>

        <form action="{{ route('transaction.disposition.store', $letter) }}" method="POST">
            @csrf
            <div class="card-body row">
                <div class="col-sm-12 col-md-6">
                    <x-input-form
                        name="to"
                        :label="__('model.disposition.to')"
                        :value="old('to', '')"
                        :required="true"
                    />
                </div>

                <div class="col-sm-12 col-md-6">
                    <x-input-form
                        name="due_date"
                        :label="__('model.disposition.due_date')"
                        type="date"
                        :value="old('due_date', '')"
                        :required="true"
                    />
                </div>

                <div class="col-sm-12">
                    <x-input-textarea-form
                        name="content"
                        :label="__('model.disposition.content')"
                        :value="old('content', '')"
                        :required="true"
                    />
                </div>

                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="mb-3">
                        <label for="letter_status" class="form-label">{{ __('model.disposition.status') }}</label>
                        <select class="form-select @error('letter_status') is-invalid @enderror" id="letter_status" name="letter_status" required>
                            <option value="" disabled {{ old('letter_status') ? '' : 'selected' }}>
                                -- Pilih {{ __('model.disposition.status') }} --
                            </option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" @selected(old('letter_status') == $status->id)>
                                    {{ $status->status }}
                                </option>
                            @endforeach
                        </select>
                        <span class="error invalid-feedback">
                            {{ $errors->first('letter_status') }}
                        </span>
                    </div>
                </div>

                <div class="col-sm-12 col-md-6 col-lg-8">
                    <x-input-form
                        name="note"
                        :label="__('model.disposition.note')"
                        :value="old('note', '')"
                    />
                </div>
            </div>

            <div class="card-footer pt-0 d-flex gap-2">
                <button class="btn btn-primary" type="submit">
                    {{ __('menu.general.save') }}
                </button>
                <a href="{{ route('transaction.disposition.index', $letter) }}" class="btn btn-outline-secondary">
                    {{ __('menu.general.cancel') }}
                </a>
            </div>
        </form>
    </div>
@endsection
