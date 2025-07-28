<?php

namespace App\Http\Controllers;

use App\Models\Clan;
use App\Http\Requests\StoreClanRequest;
use App\Http\Requests\UpdateClanRequest;
use App\Http\Resources\ClanResource;
use App\Http\Resources\ClansummaryResource;
use Illuminate\Support\Facades\Gate;

class ClanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Clan::class);
        return ClansummaryResource::collection(Clan::cursorPaginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClanRequest $request)
    {
        Gate::authorize('create', Clan::class);
        $clan = Clan::create($request->validated());
        return new ClansummaryResource($clan);
    }

    /**
     * Display the specified resource.
     */
    public function show(Clan $clan)
    {
        Gate::authorize('view', $clan);
        $clan->load(['leader', 'requeriments', 'members']);
        return new ClanResource($clan);
    }

    public function showMembers(Clan $clan)
    {
        Gate::authorize('view', $clan);
        return $clan->members;
    }

    public function showRequeriments(Clan $clan)
    {
        Gate::authorize('view', $clan);
        return $clan->requeriments;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClanRequest $request, Clan $clan)
    {
        Gate::authorize('update', $clan);
        $clan->update($request->validated());
        return new ClansummaryResource($clan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clan $clan)
    {
        Gate::authorize('delete', $clan);
        $clan->delete();
        return response()->noContent();
    }

    public function restore(string $id)
    {
        $clan = Clan::withTrashed()->findOrFail($id);
        Gate::authorize('restore', $clan);
        $clan->restore();
        return new ClansummaryResource($clan);
    }

    public function forceDelete(string $id)
    {
        $clan = Clan::withTrashed()->findOrFail($id);
        Gate::authorize('forceDelete', $clan);
        $clan->forceDelete();
        return response()->noContent();
    }
}
