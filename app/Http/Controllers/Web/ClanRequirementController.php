<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ClanRequirement;
use App\Http\Requests\StoreClanRequirementRequest;
use App\Http\Requests\UpdateClanRequirementRequest;

class ClanRequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClanRequirementRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ClanRequirement $clanRequirement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClanRequirement $clanRequirement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClanRequirementRequest $request, ClanRequirement $clanRequirement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClanRequirement $clanRequirement)
    {
        //
    }
}
