@extends('layout.main')

@section('content')
    <x-breadcrumb
        :values="[__('menu.transaction.menu'), __('menu.transaction.incoming_letter'), __('menu.general.create')]">
    </x-breadcrumb>

    <div class="card mb-4">
        <form action="{{ route('transaction.incoming.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card-body row">
                <input type="hidden" name="type" value="incoming">

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
                        name="reference_number"
                        :label="__('model.letter.reference_number')"/>
                </div>

                <div class="col-sm-12 col-12 col-md-6 col-lg-4">
                    <x-input-form
                        name="from"
                        :label="__('model.letter.from')"/>
                </div>

                <div class="col-sm-12 col-12 col-md-6 col-lg-4">
                    <x-input-form
                        name="agenda_number"
                        :label="__('model.letter.agenda_number')"/>
                </div>

                <div class="col-sm-12 col-12 col-md-6 col-lg-6">
                    <x-input-form
                        name="letter_date"
                        :label="__('model.letter.letter_date')"
                        type="date"/>
                </div>

                <div class="col-sm-12 col-12 col-md-6 col-lg-6">
                    <x-input-form
                        name="received_date"
                        :label="__('model.letter.received_date')"
                        type="date"/>
                </div>

                <div class="col-sm-12 col-12 col-md-12 col-lg-12">
                    <x-input-textarea-form
                        name="description"
                        :label="__('model.letter.description')"/>
                </div>

                <div class="col-sm-12 col-12 col-md-6 col-lg-4">
                    <x-input-form
                        name="note"
                        :label="__('model.letter.note')"/>
                </div>

                <div class="col-sm-12 col-12 col-md-6 col-lg-4">
                    <div class="mb-3">
                        <label
                            for="attachments"
                            class="form-label">
                            {{ __('model.letter.attachment') }}
                        </label>

                        <input
                            type="file"
                            class="form-control @error('attachments') is-invalid @enderror"
                            id="attachments"
                            name="attachments[]"
                            multiple>

                        <span class="invalid-feedback">
                            {{ $errors->first('attachments') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-footer pt-0">
                <button
                    class="btn btn-primary"
                    type="submit">
                    {{ __('menu.general.save') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('style')
    <link href="{{ asset('sneat/vendor/libs/select2/select2.min.css') }}" rel="stylesheet"/>
@endpush

@push('script')
<script src="{{ asset('sneat/vendor/libs/select2/select2.js') }}"></script>
    <script>
        globalThis.incomingLetterCreateJS = (function ($) {
            "use strict";

            const pub = {
                init: init,
                initModule: initModule,
            };

            function init() {
                initSelect2();
                loadRootClassification();
                initEvents();
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

                    resetChildren(currentSelect.attr("id"));

                    if (selectedId && childSelector) {
                        loadChildren(selectedId, childSelector);
                    }

                    generateReferenceNumber();
                });
            }

            function loadRootClassification() {
                $.ajax({
                    url: "{{ route('reference.archive-classifications.children') }}",
                    type: "GET",

                    success: function (response) {
                        populateSelect(
                            "#classification_level_1",
                            response
                        );
                    }
                });
            }

            function loadChildren(parentId, selector) {
                $.ajax({
                    url: `{{ route('reference.archive-classifications.children') }}/${parentId}`,
                    type: "GET",

                    success: function (response) {
                        populateSelect(selector, response);
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
                            data-code="${item.code}">
                            ${item.code} - ${item.name}
                        </option>
                    `);
                });

                select.prop("disabled", false);

                select.trigger("change.select2");
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
                        $(selector)
                            .empty()
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
                const level1 = $("#classification_level_1 option:selected").data("code");
                const level2 = $("#classification_level_2 option:selected").data("code");
                const level3 = $("#classification_level_3 option:selected").data("code");
                const level4 = $("#classification_level_4 option:selected").data("code");

                if (!level1 || !level2 || !level3 || !level4) {
                    $("#reference_number").val("");

                    return;
                }

                const classificationCode = [
                    level1,
                    level2,
                    level3,
                    level4,
                ].join(".");

                const sequence = "001";

                const monthRoman = getRomanMonth(
                    new Date().getMonth() + 1
                );

                const year = new Date().getFullYear();

                const referenceNumber = `${classificationCode}/${sequence}/UM-KESRA/RPA/${monthRoman}/${year}`;

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
            globalThis.incomingLetterCreateJS.initModule(
                globalThis.incomingLetterCreateJS
            );
        });
    </script>
@endpush
