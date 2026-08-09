<?php

namespace App\Http\Controllers;

use App\Enums\LetterType;
use App\Http\Requests\StoreLetterRequest;
use App\Http\Requests\UpdateLetterRequest;
use App\Models\Attachment;
use App\Models\ArchiveClassification;
use App\Models\Classification;
use App\Models\Config;
use App\Models\Letter;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class OutgoingLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        return view('pages.transaction.outgoing.index', [
            'data' => Letter::outgoing()->orderBy('letter_date', 'DESC')->render($request->search),
            'search' => $request->search,
        ]);
    }

    /**
     * Display a listing of the outgoing letter agenda.
     *
     * @param Request $request
     * @return View
     */
    public function agenda(Request $request): View
    {
        $perPage = $request->per_page ?? 10;
        $filter = $request->filter ?? 'letter_date';
        $since = $request->since;
        $until = $request->until;

        $data = Letter::outgoing()
            ->withoutTrashed()
            ->agenda($since, $until, $filter)
            ->orderBy('letter_date', 'DESC')
            ->render($request->search, $perPage);

        return view('pages.transaction.outgoing.agenda', [
            'data' => $data,
            'search' => $request->search,
            'since' => $since,
            'until' => $until,
            'filter' => $filter,
            'query' => http_build_query(array_merge($request->query(), ['filter' => $filter])),
            'perPage' => $perPage,
        ]);
    }

    /**
     * Display a listing of the outgoing letter agenda.
     *
     * @param Request $request
     * @return View
     */
    public function agendaArchived(Request $request): View
    {
        $perPage = $request->per_page ?? 10;
        $filter = $request->filter ?? 'letter_date';
        $selectedYear = $request->filled('year') ? (int) $request->year : null;
        $since = $request->since ?? ($selectedYear ? sprintf('%d-01-01', $selectedYear) : null);
        $until = $request->until ?? ($selectedYear ? sprintf('%d-12-31', $selectedYear) : null);
        $folderStartYear = $request->filled('start_year') ? (int) $request->start_year : null;
        $folderEndYear = $request->filled('end_year') ? (int) $request->end_year : null;
        $archiveYearExpression = 'COALESCE(letters.year, YEAR(letters.letter_date), YEAR(letters.created_at))';

        if ($folderStartYear && $folderEndYear && $folderStartYear > $folderEndYear) {
            [$folderStartYear, $folderEndYear] = [$folderEndYear, $folderStartYear];
        }

        $allArchiveYears = Letter::outgoing()
            ->onlyTrashed()
            ->selectRaw("$archiveYearExpression as archive_year, COUNT(*) as total")
            ->groupByRaw($archiveYearExpression)
            ->orderByDesc('archive_year')
            ->get();

        $archiveYearValues = $allArchiveYears
            ->pluck('archive_year')
            ->filter()
            ->map(fn ($year) => (int) $year)
            ->values();

        $archiveYearOptions = $archiveYearValues->count()
            ? collect(range($archiveYearValues->max(), $archiveYearValues->min()))
            : collect();

        $archiveYearsQuery = Letter::outgoing()
            ->onlyTrashed()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);
                $keyword = '%' . $search . '%';

                return $query->where(function ($query) use ($keyword) {
                    $query->where('reference_number', 'LIKE', $keyword)
                        ->orWhere('agenda_number', 'LIKE', $keyword)
                        ->orWhere('from', 'LIKE', $keyword)
                        ->orWhere('to', 'LIKE', $keyword)
                        ->orWhere('description', 'LIKE', $keyword)
                        ->orWhere('note', 'LIKE', $keyword);
                });
            })
            ->when($folderStartYear, function ($query) use ($archiveYearExpression, $folderStartYear) {
                return $query->whereRaw("$archiveYearExpression >= ?", [$folderStartYear]);
            })
            ->when($folderEndYear, function ($query) use ($archiveYearExpression, $folderEndYear) {
                return $query->whereRaw("$archiveYearExpression <= ?", [$folderEndYear]);
            });

        $archiveYears = $archiveYearsQuery
            ->selectRaw("$archiveYearExpression as archive_year, COUNT(*) as total")
            ->groupByRaw($archiveYearExpression)
            ->orderByDesc('archive_year')
            ->get();

        $data = null;
        $hasSelectedYear = filled($selectedYear);

        if ($hasSelectedYear) {
            $data = Letter::outgoing()
                ->onlyTrashed()
                ->whereRaw("$archiveYearExpression = ?", [$selectedYear])
                ->agenda($since, $until, $filter)
                ->render(null, $perPage);
        }

        $printQuery = http_build_query(array_filter([
            'archived' => 1,
            'year' => $selectedYear,
            'since' => $since,
            'until' => $until,
            'filter' => $filter,
        ], fn ($value) => filled($value)));

        return view('pages.transaction.outgoing.agenda-archived', [
            'data' => $data,
            'archiveYears' => $archiveYears,
            'archiveYearOptions' => $archiveYearOptions,
            'selectedYear' => $selectedYear,
            'folderStartYear' => $folderStartYear,
            'folderEndYear' => $folderEndYear,
            'hasSelectedYear' => $hasSelectedYear,
            'search' => $request->search,
            'since' => $since,
            'until' => $until,
            'filter' => $filter,
            'perPage' => $perPage,
            'query' => $printQuery,
        ]);
    }

    public function print(Request $request): View
    {
        $agenda = __('menu.agenda.menu');
        $letter = __('menu.agenda.outgoing_letter');
        $title = App::getLocale() == 'id' ? "$agenda $letter" : "$letter $agenda";
        $filter = $request->filter ?? 'letter_date';
        $selectedYear = $request->filled('year') ? (int) $request->year : null;
        $since = $request->since;
        $until = $request->until;
        $archiveYearExpression = 'COALESCE(letters.year, YEAR(letters.letter_date), YEAR(letters.created_at))';

        $data = Letter::outgoing()
            ->when($request->boolean('archived'), function ($query) {
                return $query->onlyTrashed();
            })
            ->when($request->boolean('archived') && $selectedYear, function ($query) use ($archiveYearExpression, $selectedYear) {
                return $query->whereRaw("$archiveYearExpression = ?", [$selectedYear]);
            })
            ->agenda($since, $until, $filter)
            ->get();

        return view('pages.transaction.outgoing.print', [
            'data' => $data,
            'search' => $request->search,
            'since' => $since,
            'until' => $until,
            'filter' => $filter,
            'config' => Config::pluck('value','code')->toArray(),
            'title' => $title,
        ]);
    }

    public function create(): View
    {
        $agendaFormat = sprintf('SK-%s-[XXX]', date('Ymd'));

        return view('pages.transaction.outgoing.create', [
            'classifications' => Classification::all(),
            'agendaFormat' => $agendaFormat,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLetterRequest $request
     * @return RedirectResponse
     */
    public function store(StoreLetterRequest $request): RedirectResponse
    {
        try {
            $user = auth()->user();

            if ($request->type != LetterType::OUTGOING->type()) {
                throw new \Exception(__('menu.transaction.outgoing_letter'));
            }

            $newLetter = $request->validated();
            $newLetter['user_id'] = $user->id;
            $newLetter['is_read'] = false;
            $latestSequence = Letter::withTrashed()
                ->where('year', date('Y'))
                ->where('type', LetterType::OUTGOING->type())
                ->pluck('reference_number')
                ->map(function ($referenceNumber) {
                    $parts = explode('/', (string) $referenceNumber);
                    $sequence = $parts[1] ?? null;

                    return is_numeric($sequence) ? (int) $sequence : 0;
                })
                ->max() ?? 0;

            $sequenceNumber = str_pad((string) ($latestSequence + 1), 3, '0', STR_PAD_LEFT);
            $newLetter['reference_number'] = str_replace('[XXX]', $sequenceNumber, $newLetter['reference_number']);
            $newLetter['year'] = date('Y');

            $sequenceNumber = Letter::query()
                ->where('year', date('Y'))
                ->where('type', LetterType::OUTGOING->type())
                ->count() + 1;
            $agendaNumber = str_pad((string) $sequenceNumber, 4, '0', STR_PAD_LEFT);
            $newLetter['agenda_number'] = str_replace('[XXX]', $agendaNumber, $newLetter['agenda_number']);

            $letter = Letter::create($newLetter);

            if ($request->hasFile('attachments')) {
                foreach ($request->attachments as $attachment) {
                    $extension = $attachment->getClientOriginalExtension();

                    if (!in_array($extension, ['png', 'jpg', 'jpeg', 'pdf'])) {
                        continue;
                    }

                    $filename = time() . '-'. $attachment->getClientOriginalName();
                    $filename = str_replace(' ', '-', $filename);
                    $attachment->storeAs('public/attachments', $filename);

                    Attachment::create([
                        'filename' => $filename,
                        'extension' => $extension,
                        'user_id' => $user->id,
                        'letter_id' => $letter->id,
                    ]);
                }
            }
            return redirect()
                ->route('transaction.outgoing.index')
                ->with('success', __('menu.general.success'));
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Letter $outgoing
     * @return View
     */
    public function show(Letter $outgoing): View
    {
        if (!$outgoing->is_read) {
            $outgoing->update(['is_read' => true]);
        }

        return view('pages.transaction.outgoing.show', [
            'data' => $outgoing->load(['classification', 'user', 'attachments']),
        ]);
    }

    public function showArchived($id): View
    {
        $outgoing = Letter::outgoing()
            ->onlyTrashed()
            ->with(['classification', 'user', 'attachments'])
            ->findOrFail($id);

        return view('pages.transaction.outgoing.show', [
            'data' => $outgoing,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Letter $outgoing
     * @return View
     */
    public function edit(Letter $outgoing): View
    {
        $classification = ArchiveClassification::query()
            ->where('full_code', $outgoing->classification_code)
            ->first();

        $selectedClassifications = [];

        while ($classification) {
            array_unshift($selectedClassifications, [
                'id' => $classification->id,
                'code' => $classification->code,
                'name' => $classification->name,
                'parent_id' => $classification->parent_id,
            ]);

            $classification = $classification->parent;
        }

        return view('pages.transaction.outgoing.edit', [
            'data' => $outgoing,
            'selectedClassifications' => $selectedClassifications,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateLetterRequest $request
     * @param Letter $outgoing
     * @return RedirectResponse
     */
    public function update(UpdateLetterRequest $request, Letter $outgoing): RedirectResponse
    {
        try {
            $outgoing->update($request->validated());

            if ($request->hasFile('attachments')) {
                foreach ($request->attachments as $attachment) {
                    $extension = $attachment->getClientOriginalExtension();

                    if (!in_array($extension, ['png', 'jpg', 'jpeg', 'pdf'])) {
                        continue;
                    }

                    $filename = time() . '-'. $attachment->getClientOriginalName();
                    $filename = str_replace(' ', '-', $filename);
                    $attachment->storeAs('public/attachments', $filename);

                    Attachment::create([
                        'filename' => $filename,
                        'extension' => $extension,
                        'user_id' => auth()->user()->id,
                        'letter_id' => $outgoing->id,
                    ]);
                }
            }

            return back()->with('success', __('menu.general.success'));
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Letter $outgoing
     * @return RedirectResponse
     */
    public function destroy(Letter $outgoing): RedirectResponse
    {
        try {
            $outgoing->delete();
            return redirect()
                ->route('transaction.outgoing.index')
                ->with('success', __('menu.general.success'));
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}
