<?php

namespace App\Http\Controllers;

use App\Models\SousModele;
use Illuminate\Http\Request;

class SousModeleController extends Controller
{
    /**
     * Liste tous les sous-modèles disponibles.
     */
    public function index()
    {
        return response()->json(SousModele::all());
    }

    /**
     * Créer un nouveau sous-modèle.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_sous_modele' => 'required|string|max:32|unique:sous_modeles,code_sous_modele',
            'titre' => 'required|string|max:255',
            'texte' => 'required|array', // On attend un JSON comme { "contenu": "..." }
        ]);

        $sousModele = SousModele::create([
            'code_sous_modele' => $validated['code_sous_modele'],
            'titre' => $validated['titre'],
            'texte' => $validated['texte'],
            'pdf_path' => null, // au cas où tu gardes la colonne (sinon tu peux retirer)
        ]);

        return response()->json([
            'message' => 'Sous-modèle créé avec succès.',
            'data' => $sousModele
        ], 201);
    }

    /**
     * Affiche un sous-modèle spécifique.
     */
    public function show($code)
    {
        $sousModele = SousModele::where('code_sous_modele', $code)->firstOrFail();
        return response()->json($sousModele);
    }

    /**
     * Mettre à jour un sous-modèle existant.
     */
    public function update(Request $request, $code)
    {
        $sousModele = SousModele::where('code_sous_modele', $code)->firstOrFail();

        $validated = $request->validate([
            'titre' => 'sometimes|required|string|max:255',
            'texte' => 'sometimes|required|array',
        ]);

        $sousModele->update($validated);

        return response()->json([
            'message' => 'Sous-modèle mis à jour avec succès.',
            'data' => $sousModele
        ]);
    }

    /**
     * Supprimer un sous-modèle.
     */
    public function destroy($code)
    {
        $sousModele = SousModele::where('code_sous_modele', $code)->firstOrFail();
        $sousModele->delete();

        return response()->json([
            'message' => 'Sous-modèle supprimé avec succès.'
        ], 204);
    }
}
