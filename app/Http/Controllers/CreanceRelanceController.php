<?php

namespace App\Http\Controllers;

use App\Models\CreanceRelance;
use Illuminate\Http\Request;

class CreanceRelanceController extends Controller
{
    // ✅ Affiche toutes les créances
    public function index()
    {
        return response()->json(CreanceRelance::all(), 200);
    }

    // ✅ Crée une nouvelle créance
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre_creance' => 'nullable|string|max:30',
            'ordre_creance' => 'nullable|string|max:2',
            'date_creance' => 'nullable|date',
            'actif' => 'nullable|boolean',
        ]);

        $creance = CreanceRelance::create($validated);
        return response()->json($creance, 201);
    }

    // ✅ Affiche une créance spécifique
    public function show($id)
    {
        $creance = CreanceRelance::find($id);
        if (!$creance) {
            return response()->json(['message' => 'Créance non trouvée'], 404);
        }

        return response()->json($creance);
    }

    // ✅ Met à jour une créance
    public function update(Request $request, $id)
    {
        $creance = CreanceRelance::find($id);
        if (!$creance) {
            return response()->json(['message' => 'Créance non trouvée'], 404);
        }

        $validated = $request->validate([
            'titre_creance' => 'nullable|string|max:30',
            'ordre_creance' => 'nullable|string|max:2',
            'date_creance' => 'nullable|date',
            'actif' => 'nullable|boolean',
        ]);

        $creance->update($validated);
        return response()->json($creance);
    }

    // ✅ Supprime une créance
    public function destroy($id)
    {
        $creance = CreanceRelance::find($id);
        if (!$creance) {
            return response()->json(['message' => 'Créance non trouvée'], 404);
        }

        $creance->delete();
        return response()->json(['message' => 'Créance supprimée avec succès']);
    }
}
