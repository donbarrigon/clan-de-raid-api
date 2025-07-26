<?php

namespace App\Http\Controllers;

use App\Models\ClanRequirement;
use App\Http\Requests\StoreClanRequirementRequest;
use App\Http\Requests\UpdateClanRequirementRequest;
use App\Models\Clan;
use Illuminate\Support\Facades\Gate;

class ClanRequirementController extends Controller
{
    public function store(StoreClanRequirementRequest $request)
    {
        Gate::authorize('create', ClanRequirement::class);
        return ClanRequirement::create($request->validated());
    }

    public function update(UpdateClanRequirementRequest $request, ClanRequirement $clanRequirement)
    {
        Gate::authorize('update', $clanRequirement);
        $clanRequirement->update($request->validated());
        return $clanRequirement;
    }

    public function destroy(ClanRequirement $clanRequirement)
    {
        Gate::authorize('delete', $clanRequirement);
        $clanRequirement->forceDelete();
        return response()->json(['message' => 'Requeriment deleted successfully.']);
    }
}
