<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreatePreset;
use App\Actions\DestroyPreset;
use App\Actions\UpdatePreset;
use App\Http\Requests\StorePresetRequest;
use App\Http\Requests\UpdatePresetRequest;
use App\Models\Preset;
use Illuminate\Support\Facades\Gate;

class PresetController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePresetRequest $request, CreatePreset $createPreset): void
    {
        $createPreset->handle($request->user(), $request->validated());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePresetRequest $request, Preset $preset, UpdatePreset $updatePreset): void
    {
        Gate::authorize('update', $preset);

        $updatePreset->handle($preset, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Preset $preset, DestroyPreset $destroyPreset): void
    {
        Gate::authorize('delete', $preset);

        $destroyPreset->handle($preset);
    }
}
