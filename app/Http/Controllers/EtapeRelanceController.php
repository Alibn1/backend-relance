<?php

namespace App\Http\Controllers;

use App\Models\EtapeRelance;
use Illuminate\Http\Request;

class EtapeRelanceController extends Controller
{
    /**
     * Lister toutes les étapes de relance avec relations.
     */
    public function index()
    {
        $etapes = EtapeRelance::with([
            'relanceDossier',
            'statutRelanceDetail',
            'client',
            'eventRelances',
            'situationRelances'
        ])->get();

        return response()->json($etapes);
    }

    /**
     * Créer une nouvelle étape de relance.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            //'numero_relance' => 'required|string|max:8|unique:etape_relances',
            'numero_relance_dossier' => 'required|string|max:8|exists:relance_dossiers,numero_relance_dossier',
            'code_client' => 'required|string|exists:clients,code_client',
            'code_sous_modele' => 'nullable|string|max:8',
            'titre_sous_modele' => 'nullable|string|max:30',
            'ordre' => 'nullable|string|max:2',
            'statut_detail' => 'nullable|string|exists:statut_relance_detail,code',
            'date_par_statut' => 'nullable|date',
            'etat_relance_actif' => 'nullable|string|max:1',
            'date_rappel' => 'nullable|date',
            'nombre_jour_rappel' => 'nullable|integer',
            'methode_envoi' => 'nullable|string|max:30',
            'executant_envoi' => 'nullable|string|max:25',
            'date_creation_debut' => 'nullable|date',
            'date_creation_fin' => 'nullable|date',
            'etape_actif' => 'nullable|string|max:1',
            'objet_relance_1' => 'nullable|string|max:50',
            'objet_relance_2' => 'nullable|string|max:50',
        ]);

        $etape = EtapeRelance::create($validated);

        return response()->json($etape, 201);
    }

    /**
     * Afficher une étape de relance spécifique.
     */
    public function show($id)
    {
        $etape = EtapeRelance::with([
            'relanceDossier',
            'statutRelanceDetail',
            'client',
            'eventRelances',
            'situationRelances'
        ])->findOrFail($id);

        return response()->json($etape);
    }

    /**
     * Mettre à jour une étape de relance.
     */
    public function update(Request $request, $id)
    {
        $etape = EtapeRelance::findOrFail($id);

        $validated = $request->validate([
            'numero_relance_dossier' => 'sometimes|required|string|max:8|exists:relance_dossiers,numero_relance_dossier',
            'code_client' => 'sometimes|required|string|exists:clients,code_client',
            'code_sous_modele' => 'nullable|string|max:8',
            'titre_sous_modele' => 'nullable|string|max:30',
            'ordre' => 'nullable|string|max:2',
            'statut_detail' => 'nullable|string|exists:statut_relance_detail,code',
            'date_par_statut' => 'nullable|date',
            'etat_relance_actif' => 'nullable|string|max:1',
            'date_rappel' => 'nullable|date',
            'nombre_jour_rappel' => 'nullable|integer',
            'methode_envoi' => 'nullable|string|max:30',
            'executant_envoi' => 'nullable|string|max:25',
            'date_creation_debut' => 'nullable|date',
            'date_creation_fin' => 'nullable|date',
            'etape_actif' => 'nullable|string|max:1',
            'objet_relance_1' => 'nullable|string|max:50',
            'objet_relance_2' => 'nullable|string|max:50',
        ]);

        $etape->update($validated);

        return response()->json($etape);
    }

    /**
     * Supprimer une étape de relance.
     */
    public function destroy($id)
    {
        $etape = EtapeRelance::findOrFail($id);
        $etape->delete();

        return response()->json(null, 204);
    }

    public function scopeActives($query)
    {
        return $query->where('etape_actif', '1');
    }

}
