<?php

namespace App\Http\Controllers;

use App\Models\ArchiveClassification;
use App\Http\Requests\StoreArchiveClassificationRequest;
use App\Http\Requests\UpdateArchiveClassificationRequest;

class ArchiveClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.reference.archive-classification.index');
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
}
