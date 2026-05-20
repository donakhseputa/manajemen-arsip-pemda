@extends('layout.main')

@section('content')
    <x-breadcrumb
        :values="[__('menu.transaction.menu'), __('menu.transaction.incoming_letter'), __('menu.general.edit')]">
    </x-breadcrumb>

    <div class="card mb-4">
        @include('layout.partials.alert')

        <form action="{{ route('transaction.incoming.update', $data) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card-body row">
                <input type="hidden" name="id" value="{{ $data->id }}">
                <input type="hidden" name="type" value="{{ $data->type }}">
                <input
                    type="hidden"
                    name="classification_code"
                    id="classification_code_input"
                    value="{{ $data->classification_code }}">

                {{-- Classification Level 1 --}}
                <div class="col-sm-12 col-md-6 col-lg-3">
                    <div class="mb-3">
                        <label
                            for="classification_level_1"
                            class="form-label required">
                            {{ __('model.letter.classification_level_1') }}
                        </label>

                        <select
                            class="form-select classification-select @error('classification_level_1') is-invalid @enderror"
                            id="classification_level_1"
                            name="classification_level_1"
                            data-child="#classification_level_2">
                        </select>

                        <span class="invalid-feedback">
                            {{ $errors->first('classification_level_1') }}
                        </span>
                    </div>
                </div>

                {{-- Classification Level 2 --}}
                <div class="col-sm-12 col-md-6 col-lg-3">
                    <div class="mb-3">
                        <label
                            for="classification_level_2"
                            class="form-label required">
                            {{ __('model.letter.classification_level_2') }}
                        </label>

                        <select
                            class="form-select classification-select @error('classification_level_2') is-invalid @enderror"
                            id="classification_level_2"
                            name="classification_level_2"
                            data-child="#classification_level_3"
                            disabled>
                        </select>

                        <span class="invalid-feedback">
                            {{ $errors->first('classification_level_2') }}
                        </span>
                    </div>
                </div>

                {{-- Classification Level 3 --}}
                <div class="col-sm-12 col-md-6 col-lg-3">
                    <div class="mb-3">
                        <label
                            for="classification_level_3"
                            class="form-label required">
                            {{ __('model.letter.classification_level_3') }}
                        </label>

                        <select
                            class="form-select classification-select @error('classification_level_3') is-invalid @enderror"
                            id="classification_level_3"
                            name="classification_level_3"
                            data-child="#classification_level_4"
                            disabled>
                        </select>

                        <span class="invalid-feedback">
                            {{ $errors->first('classification_level_3') }}
                        </span>
                    </div>
                </div>

                {{-- Classification Level 4 --}}
                <div class="col-sm-12 col-md-6 col-lg-3">
                    <div class="mb-3">
                        <label
                            for="classification_level_4"
                            class="form-label required">
                            {{ __('model.letter.classification_level_4') }}
                        </label>

                        <select
                            class="form-select classification-select @error('classification_id') is-invalid @enderror"
                            id="classification_level_4"
                            name="classification_id"
                            disabled>
                        </select>

                        <span class="invalid-feedback">
                            {{ $errors->first('classification_id') }}
                        </span>
                    </div>
                </div>

                <div class="col-sm-12 col-12 col-md-6 col-lg-4">
                    <x-input-form
                        id="reference_number"
                        :value="$data->reference_number"
                        name="reference_number"
                        :label="__('model.letter.reference_number')"/>
                </div>

                <div class="col-sm-12 col-12 col-md-6 col-lg-4">
                    <x-input-form
                        :value="$data->from"
                        name="from"
                        :label="__('model.letter.from')"/>
                </div>

                <div class="col-sm-12 col-12 col-md-6 col-lg-4">
                    <x-input-form
                        :value="$data->agenda_number"
                        name="agenda_number"
                        :label="__('model.letter.agenda_number')"/>
                </div>

                <div class="col-sm-12 col-12 col-md-6 col-lg-6">
                    <x-input-form
                        :value="date('Y-m-d', strtotime($data->letter_date))"
                        name="letter_date"
                        :label="__('model.letter.letter_date')"
                        type="date"/>
                </div>

                <div class="col-sm-12 col-12 col-md-6 col-lg-6">
                    <x-input-form
                        :value="date('Y-m-d', strtotime($data->received_date))"
                        name="received_date"
                        :label="__('model.letter.received_date')"
                        type="date"/>
                </div>

                <div class="col-sm-12 col-12 col-md-12 col-lg-12">
                    <x-input-textarea-form
                        :value="$data->description"
                        name="description"
                        :label="__('model.letter.description')"/>
                </div>

                <div class="col-sm-12 col-12 col-md-6 col-lg-4">
                    <x-input-form
                        :value="$data->note ?? ''"
                        name="note"
                        :label="__('model.letter.note')"/>
                </div>

                <div class="col-sm-12 col-12 col-md-6 col-lg-4">
                    <div class="mb-3">
                        <label for="attachments" class="form-label">
                            {{ __('model.letter.attachment') }}
                        </label>

                        <input type="file" class="form-control @error('attachments') is-invalid @enderror"
                            id="attachments" name="attachments[]" multiple>

                        <span class="invalid-feedback">
                            {{ $errors->first('attachments') }}
                        </span>
                    </div>

                    <ul class="list-group">
                        @foreach($data->attachments as $attachment)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ $attachment->path_url }}" target="_blank">
                                    {{ $attachment->filename }}
                                </a>

                                <span
                                    class="badge bg-danger rounded-pill cursor-pointer btn-remove-attachment"
                                    data-id="{{ $attachment->id }}">
                                    <i class="bx bx-trash"></i>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="card-footer pt-0">
                <button class="btn btn-primary" type="submit">
                    {{ __('menu.general.update') }}
                </button>
            </div>
        </form>
    </div>

    <form action="{{ route('attachment.destroy') }}" method="POST" id="form-to-remove-attachment">
        @csrf
        @method('DELETE')
        <input type="hidden" name="id" id="attachment-id-to-remove">
    </form>
@endsection

@push('style')
    <link href="{{ asset('sneat/vendor/libs/select2/select2.min.css') }}" rel="stylesheet"/>
@endpush

@push('script')
    <script src="{{ asset('sneat/vendor/libs/select2/select2.js') }}"></script>

    <script>
        globalThis.incomingLetterEditJS = (function ($) {
            "use strict";

            const selectedClassifications = @json($selectedClassifications);

            const pub = {
                init: init,
                initModule: initModule,
            };

            function init() {
                initSelect2();
                initEvents();
                initRemoveAttachment();
                loadRootClassification();
            }

            function initSelect2() {
                $(".classification-select").select2({
                    width: "100%",
                    placeholder: "Select Classification",
                    allowClear: true,
                });
            }

            function initEvents() {
                $(".classification-select").on("change", function () {
                    const currentSelect = $(this);
                    const selectedId = currentSelect.val();
                    const childSelector = currentSelect.data("child");
                    const selectedOption = currentSelect.find("option:selected");
                    const hasChildren = selectedOption.data("has-children");

                    resetChildren(currentSelect.attr("id"));

                    if (selectedId && childSelector && hasChildren) {
                        loadChildren(selectedId, childSelector);
                        $("#reference_number").val("");

                        return;
                    }

                    generateReferenceNumber();
                });
            }

            function initRemoveAttachment() {
                $(document).on("click", ".btn-remove-attachment", function () {
                    $("#attachment-id-to-remove").val($(this).data("id"));

                    Swal.fire({
                        title: '{{ __('menu.general.delete_confirm') }}',
                        text: "{{ __('menu.general.delete_warning') }}",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#696cff",
                        confirmButtonText: '{{ __('menu.general.delete') }}',
                        cancelButtonText: '{{ __('menu.general.cancel') }}'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $("#form-to-remove-attachment").submit();
                        }
                    });
                });
            }

            function loadRootClassification() {
                $.ajax({
                    url: "{{ route('reference.archive-classifications.children') }}",
                    type: "GET",
                    success: function (response) {
                        populateSelect("#classification_level_1", response);
                        preselectClassification(0);
                    }
                });
            }

            function loadChildren(parentId, selector, callback = null) {
                $.ajax({
                    url: `{{ route('reference.archive-classifications.children') }}/${parentId}`,
                    type: "GET",
                    success: function (response) {
                        populateSelect(selector, response);

                        if (typeof callback === "function") {
                            callback();
                        }
                    }
                });
            }

            function populateSelect(selector, items) {
                const select = $(selector);

                select.empty();
                select.append(`
                    <option value="">
                        Select Classification
                    </option>
                `);

                $.each(items, function (_, item) {
                    select.append(`
                        <option
                            value="${item.id}"
                            data-code="${item.code}"
                            data-has-children="${item.has_children}">
                            ${item.code} - ${item.name}
                        </option>
                    `);
                });

                select.prop("disabled", false);
                select.trigger("change.select2");
            }

            function preselectClassification(index) {
                if (selectedClassifications.length <= index) {
                    generateReferenceNumber();

                    return;
                }

                const classification = selectedClassifications[index];
                const currentSelector = `#classification_level_${index + 1}`;
                const nextSelector = `#classification_level_${index + 2}`;

                $(currentSelector).val(classification.id).trigger("change.select2");

                const option = $(currentSelector).find(`option[value="${classification.id}"]`);
                const hasChildren = option.data("has-children");

                if (hasChildren) {
                    loadChildren(classification.id, nextSelector, function () {
                        preselectClassification(index + 1);
                    });
                } else
                    generateReferenceNumber();
                }
            }

            function resetChildren(currentId) {
                const hierarchy = [
                    "#classification_level_1",
                    "#classification_level_2",
                    "#classification_level_3",
                    "#classification_level_4",
                ];

                const currentIndex = hierarchy.indexOf(`#${currentId}`);

                hierarchy.forEach(function (selector, index) {
                    if (index > currentIndex) {
                        $(selector).empty()
                            .append(`
                                <option value="">
                                    Select Classification
                                </option>
                            `)
                            .val(null)
                            .trigger("change")
                            .prop("disabled", true);
                    }
                });
            }

            function generateReferenceNumber() {
                const codes = [];

                $(".classification-select").each(function () {
                    const code = $(this).find("option:selected").data("code");

                    if (code !== undefined && code !== null && code !== "") {
                        codes.push(code);
                    }
                });

                if (codes.length === 0) {
                    $("#reference_number").val("");

                    return;
                }

                const classificationCode = codes.join(".");
                const existingNumber = "{{ $data->reference_number }}";
                let sequence = "[XXX]";
                const match = existingNumber.match(/\/(\d+)\//);

                if (match) {
                    sequence = match[1];
                }

                const monthRoman = getRomanMonth(new Date().getMonth() + 1);
                const year = new Date().getFullYear();
                const referenceNumber = `${classificationCode}/${sequence}/UM-KESRA/RPA/${monthRoman}/${year}`;

                $("#classification_code_input").val(classificationCode);
                $("#reference_number").val(referenceNumber);
            }

            function getRomanMonth(month) {
                const romans = [
                    "I",
                    "II",
                    "III",
                    "IV",
                    "V",
                    "VI",
                    "VII",
                    "VIII",
                    "IX",
                    "X",
                    "XI",
                    "XII",
                ];

                return romans[month - 1];
            }

            function initModule(module) {
                if (module.isActive !== undefined && !module.isActive) {
                    return;
                }

                if (typeof module.init === "function") {
                    module.init();
                }

                $.each(module, function () {
                    if ($.isPlainObject(this)) {
                        initModule(this);
                    }
                });
            }

            return pub;
        })(globalThis.jQuery);

        globalThis.jQuery(function () {
            globalThis.incomingLetterEditJS.initModule(
                globalThis.incomingLetterEditJS
            );
        });
    </script>
@endpush
