<!doctype html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title }}</title>

        <style>
            * {
                box-sizing: border-box;
            }

            body {
                margin: 20px;
                color: #000;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
            }

            /* =========================
            LETTERHEAD
            ========================= */

            .letterhead-table {
                width: 100%;
                border-collapse: collapse;
            }

            .letterhead-table td {
                border: none;
                padding: 0;
            }

            .logo-cell {
                width: 110px;
                text-align: center;
                vertical-align: middle;
            }

            .logo-cell img {
                width: 90px;
                height: auto;
            }

            .content-cell {
                text-align: center;
                vertical-align: middle;
            }

            .title-1 {
                margin: 0;
                font-size: 22px;
                font-weight: 500;
                text-transform: uppercase;
            }

            .title-2 {
                margin: 3px 0;
                font-size: 25px;
                font-weight: 700;
                text-transform: uppercase;
            }

            .address {
                margin-top: 2px;
                font-size: 14px;
            }

            .email {
                margin-top: 2px;
                font-size: 14px;
                font-style: italic;
            }

            .email a {
                text-decoration: underline;
            }

            .header-line {
                margin-top: 10px;
                height: 6px;
                border-top: 3px solid #000;
                border-bottom: 1px solid #000;
            }

            /* =========================
            TITLE
            ========================= */

            .report-title {
                margin: 25px 0 20px;
                text-align: center;
                font-size: 18px;
                font-weight: bold;
                text-transform: uppercase;
                text-decoration: underline;
            }

            /* =========================
            FILTER
            ========================= */

            #filter-section {
                margin-bottom: 20px;
                text-align: left;
                line-height: 1.6;
            }

            /* =========================
            TABLE
            ========================= */

            .report-table {
                width: 100%;
                border-collapse: collapse;
            }

            .report-table,
            .report-table th,
            .report-table td {
                border: 1px solid #000;
            }

            .report-table th {
                padding: 8px;
                text-align: center;
                font-weight: bold;
                background-color: #f5f5f5;
            }

            .report-table td {
                padding: 8px;
                vertical-align: top;
            }

            .text-center {
                text-align: center;
            }

            @media print {
                body {
                    margin: 10mm;
                }
            }
        </style>
    </head>
    <body onload="window.print()">
        <!-- LETTERHEAD -->
        <table class="letterhead-table">
            <tbody>
            <tr>
                <td class="logo-cell">
                    <img
                        src="{{ asset('sneat/img/logo-kabupaten-asahan.png') }}"
                        alt="Logo Kabupaten Asahan">
                </td>

                <td class="content-cell">
                    <div class="title-1">
                        PEMERINTAH KABUPATEN ASAHAN
                    </div>

                    <div class="title-2">
                        KECAMATAN RAWANG PANCA ARGA
                    </div>

                    <div class="address">
                        Jalan Besar Rawang Panca Arga - Kode Pos 21264
                    </div>

                    <div class="email">
                        Email :
                        <a href="mailto:rawangpancaarga@asahankab.go.id">
                            rawangpancaarga@asahankab.go.id
                        </a>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="header-line"></div>

        <!-- TITLE -->
        <div class="report-title">
            {{ $title }}
        </div>

        <!-- FILTER -->
        @if($since && $until && $filter)
            <div id="filter-section">
                <strong>{{ __('model.letter.' . $filter) }}</strong>
                : {{ $since }} - {{ $until }}

                <br>

                <strong>Total Data</strong>
                : {{ count($data) }}
            </div>
        @endif

        <!-- DATA TABLE -->
        <table class="report-table">
            <thead>
                <tr>
                    <th width="8%">
                        {{ __('model.letter.agenda_number') }}
                    </th>

                    <th width="15%">
                        {{ __('model.letter.reference_number') }}
                    </th>

                    <th width="18%">
                        {{ __('model.letter.to') }}
                    </th>

                    <th width="12%">
                        {{ __('model.letter.letter_date') }}
                    </th>

                    <th width="27%">
                        {{ __('model.letter.description') }}
                    </th>

                    <th width="20%">
                        {{ __('model.letter.note') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $letter)
                    <tr>
                        <td class="text-center">
                            {{ $letter->agenda_number }}
                        </td>

                        <td>
                            {{ $letter->reference_number }}
                        </td>

                        <td>
                            {{ $letter->to }}
                        </td>

                        <td class="text-center">
                            {{ $letter->formatted_letter_date }}
                        </td>

                        <td>
                            {{ $letter->description }}
                        </td>

                        <td>
                            {{ $letter->note }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            Tidak ada data.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>
