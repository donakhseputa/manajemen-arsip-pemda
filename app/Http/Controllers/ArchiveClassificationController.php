<?php

namespace App\Http\Controllers;

use App\DataTables\ArchiveClassificationsDataTable;
use App\Models\ArchiveClassification;
use App\Http\Requests\StoreArchiveClassificationRequest;
use App\Http\Requests\UpdateArchiveClassificationRequest;
use Illuminate\Http\JsonResponse;

class ArchiveClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ArchiveClassificationsDataTable $dataTable)
    {
        return $dataTable->render('pages.reference.archive-classification.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.reference.archive-classification.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreArchiveClassificationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArchiveClassificationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ArchiveClassification  $archiveClassification
     * @return \Illuminate\Http\Response
     */
    public function show(ArchiveClassification $archiveClassification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ArchiveClassification  $archiveClassification
     * @return \Illuminate\Http\Response
     */
    public function edit(ArchiveClassification $archiveClassification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateArchiveClassificationRequest  $request
     * @param  \App\Models\ArchiveClassification  $archiveClassification
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateArchiveClassificationRequest $request, ArchiveClassification $archiveClassification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ArchiveClassification  $archiveClassification
     * @return \Illuminate\Http\Response
     */
    public function destroy(ArchiveClassification $archiveClassification)
    {
        //
    }

    public function children(?int $id = null): JsonResponse
    {
        $query = ArchiveClassification::query();

        if ($id) {
            $query->where('parent_id', $id);
        } else {
            $query->whereNull('parent_id');
        }

        $classifications = $query
            ->orderBy('code')
            ->get([
                'id',
                'code',
                'name',
                'parent_id',
                'level',
            ])
            ->map(function ($classification) {
                return [
                    'id' => $classification->id,
                    'code' => $classification->code,
                    'name' => $classification->name,
                    'has_children' => ArchiveClassification::query()
                        ->where('parent_id', $classification->id)
                        ->exists(),
                ];
            });

        return response()->json($classifications);
    }
}
