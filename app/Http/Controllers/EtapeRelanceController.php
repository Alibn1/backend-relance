<?php

namespace App\Http\Controllers;

use App\Models\EtapeRelance;
use App\Services\SousModelePDFService;
use Illuminate\Http\Request;

class EtapeRelanceController extends Controller
{
    protected $pdfService;

    public function __construct(SousModelePDFService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

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
            'situationRelances',
            'sousModele' // 👉 Ajout : pour charger aussi le sous-modèle lié
        ])->get();

        return response()->json($etapes);
    }

    /**
     * Créer une nouvelle étape de relance (et génère le PDF si un sous-modèle est sélectionné).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            //'numero_relance' => 'required|string|max:8|unique:etape_relances',
            'numero_relance_dossier' => 'required|string|max:8|exists:relance_dossiers,numero_relance_dossier',
            'code_client' => 'required|string|exists:clients,code_client',
            'code_sous_modele' => 'nullable|string|exists:sous_modeles,code_sous_modele',
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

        // 👉 Génération automatique du PDF si un sous-modèle est choisi
        if (!empty($validated['code_sous_modele'])) {
            $client = $etape->client;
            // $releve = $client->releves()->latest()->with('lignes')->first();
            $releve = $client->releves()->latest()->first();
            $sousModele = $etape->sousModele;

            if ($client && $releve && $sousModele) {
                $result = $this->pdfService->generatePDF($client, $releve, $sousModele);

                // Sauvegarde le chemin du PDF dans la colonne pdf_path de l'étape
                $etape->pdf_path = $result['path'];
                $etape->save();
            }
        }

        return response()->json([
            'message' => 'Étape de relance créée avec succès.',
            'data' => $etape
        ], 201);
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
            'situationRelances',
            'sousModele' // 👉 Ajout pour bien voir aussi le sous-modèle lié
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
            'code_sous_modele' => 'nullable|string|exists:sous_modeles,code_sous_modele',
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

        return response()->json([
            'message' => 'Étape de relance mise à jour avec succès.',
            'data' => $etape
        ]);
    }

    /**
     * Supprimer une étape de relance.
     */
    public function destroy($id)
    {
        $etape = EtapeRelance::findOrFail($id);
        $etape->delete();

        return response()->json([
            'message' => 'Étape de relance supprimée avec succès.'
        ], 204);
    }

    public function scopeActives($query)
    {
        return $query->where('etape_actif', '1');
    }
}
