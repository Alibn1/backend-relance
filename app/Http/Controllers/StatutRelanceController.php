<?php

namespace App\Http\Controllers;

use App\Models\StatutRelance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StatutRelanceController extends Controller
{
    public function index()
    {
        return response()->json(StatutRelance::with(['details', 'relances'])->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:statut_relance,code',
            'libelle' => 'nullable|string|max:30',
            'couleur' => 'nullable|string|max:10',
            'champ_interface' => 'nullable|string|max:20',
            'actif' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $statut = StatutRelance::create([
            ...$request->except('actif'),
            'actif' => $request->boolean('actif', true),
        ]);

        return response()->json($statut, 201);
    }

    public function show($code)
    {
        $statut = StatutRelance::with(['details', 'relances'])->find($code);

        if (!$statut) {
            return response()->json(['message' => 'Statut non trouvé'], 404);
        }

        return response()->json($statut);
    }

    public function update(Request $request, $code)
    {
        $statut = StatutRelance::find($code);

        if (!$statut) {
            return response()->json(['message' => 'Statut non trouvé'], 404);
        }

        $validator = Validator::make($request->all(), [
            'libelle' => 'nullable|string|max:30',
            'couleur' => 'nullable|string|max:10',
            'champ_interface' => 'nullable|string|max:20',
            'actif' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $statut->update([
            ...$request->except('actif'),
            'actif' => $request->boolean('actif', $statut->actif),
        ]);

        return response()->json($statut);
    }

    public function destroy($code)
    {
        $statut = StatutRelance::find($code);

        if (!$statut) {
            return response()->json(['message' => 'Statut non trouvé'], 404);
        }

        $statut->delete();

        return response()->json(['message' => 'Statut supprimé avec succès']);
    }
}
