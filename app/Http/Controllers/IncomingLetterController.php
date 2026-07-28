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
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class IncomingLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        return view('pages.transaction.incoming.index', [
            'data' => Letter::incoming()->orderBy('letter_date', 'DESC')->render($request->search),
            'search' => $request->search,
        ]);
    }

    /**
     * Display a listing of the incoming letter agenda.
     *
     * @param Request $request
     * @return View
     */
    public function agenda(Request $request): View
    {
        $perPage = $request->per_page ?? 10;
        $filter = $request->filter ?? 'received_date';
        $since = $request->since;
        $until = $request->until;

        $data = Letter::incoming()
            ->withoutTrashed()
            ->agenda($since, $until, $filter)
            ->orderBy('letter_date', 'DESC')
            ->render($request->search, $perPage);

        return view('pages.transaction.incoming.agenda', [
            'data' => $data,
            'search' => $request->search,
            'since' => $since,
            'until' => $until,
            'filter' => $filter,
            'perPage' => $perPage,
            'query' => http_build_query(array_merge($request->query(), ['filter' => $filter])),
        ]);
    }

    /**
     * Display a listing of the incoming letter agenda.
     *
     * @param Request $request
     * @return View
     */
    public function agendaArchived(Request $request): View
    {
        $perPage = $request->per_page ?? 10;
        $filter = $request->filter ?? 'received_date';
        $selectedYear = $request->filled('year') ? (int) $request->year : null;
        $since = $request->since ?? ($selectedYear ? sprintf('%d-01-01', $selectedYear) : null);
        $until = $request->until ?? ($selectedYear ? sprintf('%d-12-31', $selectedYear) : null);
        $folderStartYear = $request->filled('start_year') ? (int) $request->start_year : null;
        $folderEndYear = $request->filled('end_year') ? (int) $request->end_year : null;
        $archiveYearExpression = 'COALESCE(letters.year, YEAR(letters.received_date), YEAR(letters.letter_date), YEAR(letters.created_at))';

        if ($folderStartYear && $folderEndYear && $folderStartYear > $folderEndYear) {
            [$folderStartYear, $folderEndYear] = [$folderEndYear, $folderStartYear];
        }

        $allArchiveYears = Letter::incoming()
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

        $archiveYearsQuery = Letter::incoming()
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
            $data = Letter::incoming()
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

        return view('pages.transaction.incoming.agenda-archived', [
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

    public function archive()
    {
        try {
            $bulkIds = request()->input('ids', []);

            foreach ($bulkIds as $id) {
                $incoming = Letter::findOrFail($id);
                $incoming->delete();
            }

            if (request()->ajax()) {
                return response()->json([
                    'message' => __('menu.general.success'),
                ]);
            }

            return redirect()
                ->route('transaction.incoming.agenda')
                ->with('success', __('menu.general.success'));
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return View
     */
    public function print(Request $request): View
    {
        $agenda = __('menu.agenda.menu');
        $letter = __('menu.agenda.incoming_letter');
        $title = App::getLocale() == 'id' ? "$agenda $letter" : "$letter $agenda";
        $filter = $request->filter ?? 'received_date';
        $selectedYear = $request->filled('year') ? (int) $request->year : null;
        $since = $request->since;
        $until = $request->until;
        $archiveYearExpression = 'COALESCE(letters.year, YEAR(letters.received_date), YEAR(letters.letter_date), YEAR(letters.created_at))';

        $data = Letter::incoming()
            ->when($request->boolean('archived'), function ($query) {
                return $query->onlyTrashed();
            })
            ->when($request->boolean('archived') && $selectedYear, function ($query) use ($archiveYearExpression, $selectedYear) {
                return $query->whereRaw("$archiveYearExpression = ?", [$selectedYear]);
            })
            ->agenda($since, $until, $filter)
            ->get();

        return view('pages.transaction.incoming.print', [
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
        $agendaFormat = sprintf('SM-%s-[XXX]', date('Ymd'));

        return view('pages.transaction.incoming.create', [
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

            if ($request->type != LetterType::INCOMING->type()) {
                throw new \Exception(__('menu.transaction.incoming_letter'));
            }

            $newLetter = $request->validated();
            $newLetter['user_id'] = $user->id;
            $newLetter['year'] = date('Y');
            $sequenceNumber = Letter::query()
                ->where('year', date('Y'))
                ->where('type', LetterType::INCOMING->type())
                ->count() + 1;
            $agendaNumber = str_pad((string) $sequenceNumber, 4, '0', STR_PAD_LEFT);
            $newLetter['agenda_number'] = str_replace('[XXX]', $agendaNumber, $newLetter['agenda_number']);
            $letter = Letter::create($newLetter);

            if ($request->hasFile('attachments')) {
                foreach ($request->attachments as $attachment) {
                    $extension = $attachment->getClientOriginalExtension();

                    if (!in_array($extension, ['png', 'jpg', 'jpeg', 'pdf'])){
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
                ->route('transaction.incoming.index')
                ->with('success', __('menu.general.success'));
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Letter $incoming
     * @return View
     */
    public function show(Letter $incoming): View
    {
        return view('pages.transaction.incoming.show', [
            'data' => $incoming->load(['classification', 'user', 'attachments']),
        ]);
    }

    public function showArchived($id): View
    {
        $incoming = Letter::incoming()
            ->onlyTrashed()
            ->with(['classification', 'user', 'attachments'])
            ->findOrFail($id);

        return view('pages.transaction.incoming.show', [
            'data' => $incoming,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Letter $incoming
     * @return View
     */
    public function edit(Letter $incoming): View
    {
        return view('pages.transaction.incoming.edit', [
            'data' => $incoming,
            'classifications' => Classification::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateLetterRequest $request
     * @param Letter $incoming
     * @return RedirectResponse
     */
    public function update(UpdateLetterRequest $request, Letter $incoming): RedirectResponse
    {
        try {
            $incoming->update($request->validated());
            if ($request->hasFile('attachments')) {
                foreach ($request->attachments as $attachment) {
                    $extension = $attachment->getClientOriginalExtension();
                    if (!in_array($extension, ['png', 'jpg', 'jpeg', 'pdf'])) continue;
                    $filename = time() . '-'. $attachment->getClientOriginalName();
                    $filename = str_replace(' ', '-', $filename);
                    $attachment->storeAs('public/attachments', $filename);
                    Attachment::create([
                        'filename' => $filename,
                        'extension' => $extension,
                        'user_id' => auth()->user()->id,
                        'letter_id' => $incoming->id,
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
     * @param Letter $incoming
     * @return RedirectResponse
     */
    public function destroy(Letter $incoming): RedirectResponse
    {
        try {
            $incoming->delete();
            return redirect()
                ->route('transaction.incoming.index')
                ->with('success', __('menu.general.success'));
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}
