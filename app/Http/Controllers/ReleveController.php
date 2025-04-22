<?php

namespace App\Http\Controllers;

use App\Models\Releve;
use Illuminate\Http\Request;

class ReleveController extends Controller
{
    /**
     * Lister tous les relevés avec les relations.
     */
    public function index()
    {
        $releves = Releve::with('client')->get();
        return response()->json($releves);
    }

    /**
     * Créer un nouveau relevé.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_client' => 'required|string|exists:clients,code_client',
            'date_releve' => 'required|date',
            'solde_initiale' => 'required|numeric',
            'solde_finale' => 'required|numeric',
            'statut' => 'nullable|string|max:20',
            'commentaire' => 'nullable|string',
            'created_by' => 'required|string|max:25',
        ]);

        $releve = Releve::create($validated);

        return response()->json($releve, 201);
    }

    /**
     * Afficher un relevé spécifique.
     */
    public function show($id)
    {
        $releve = Releve::with('client')->findOrFail($id);
        return response()->json($releve);
    }

    /**
     * Mettre à jour un relevé.
     */
    public function update(Request $request, $id)
    {
        $releve = Releve::findOrFail($id);

        $validated = $request->validate([
            'code_client' => 'sometimes|required|string|exists:clients,code_client',
            'date_releve' => 'sometimes|required|date',
            'solde_initiale' => 'sometimes|required|numeric',
            'solde_finale' => 'sometimes|required|numeric',
            'statut' => 'nullable|string|max:20',
            'commentaire' => 'nullable|string',
            'created_by' => 'sometimes|required|string|max:25',
        ]);

        $releve->update($validated);

        return response()->json($releve);
    }

    /**
     * Supprimer (soft delete) un relevé.
     */
    public function destroy($id)
    {
        $releve = Releve::findOrFail($id);
        $releve->delete();

        return response()->json(null, 204);
    }

    /**
     * Restaurer un relevé supprimé (soft deleted).
     */
    public function restore($id)
    {
        $releve = Releve::onlyTrashed()->findOrFail($id);
        $releve->restore();

        return response()->json($releve);
    }

    /**
     * Liste des relevés supprimés (optionnel).
     */
    public function trashed()
    {
        $trashed = Releve::onlyTrashed()->with('client')->get();
        return response()->json($trashed);
    }
}
