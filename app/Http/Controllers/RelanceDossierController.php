<?php

namespace App\Http\Controllers;

use App\Models\RelanceDossier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\StatutRelance;

class RelanceDossierController extends Controller
{
    public function index()
    {
        return response()->json(RelanceDossier::with(['client', 'statut', 'etapeRelances'])->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero_relance_dossier' => 'nullable|string|max:12|unique:relance_dossiers,numero_relance_dossier',
            'date_relance_dossier' => 'nullable|date',
            'code_client' => 'required|string|exists:clients,code_client',
            'contact_interlocuteur' => 'nullable|string|max:25',
            'utilisateur_creation' => 'nullable|string|max:25',
            'utilisateur_modification' => 'nullable|string|max:25',
            'horodatage_creation' => 'nullable|date_format:H:i:s',
            'horodatage_fin' => 'nullable|date_format:H:i:s',
            'horodatage_modification' => 'nullable|date_format:H:i:s',
            'code_modele' => 'nullable|string|max:8',
            'statut' => 'required|string|exists:statut_relance,code',
            'date_par_statut' => 'nullable|date',
            'actif' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $dossier = RelanceDossier::create([
            ...$request->except('actif'),
            'actif' => $request->boolean('actif', true),
        ]);

        return response()->json($dossier, 201);
    }

    public function show($id)
    {
        $dossier = RelanceDossier::with(['client', 'statut', 'etapeRelances'])
        ->where('numero_relance_dossier', $id)
        ->first();

        if (!$dossier) {
        return response()->json([
            'success' => false,
            'message' => 'Dossier non trouvé'
            ], 404);
        }

        return response()->json([
        'success' => true,
        'data' => $dossier
        ]);
    }

    public function update(Request $request, $id)
    {
        $dossier = RelanceDossier::find($id);

        if (!$dossier) {
            return response()->json(['message' => 'Dossier non trouvé'], 404);
        }

        $validator = Validator::make($request->all(), [
            'date_relance_dossier' => 'nullable|date',
            'code_client' => 'nullable|string|exists:clients,code_client',
            'contact_interlocuteur' => 'nullable|string|max:25',
            'utilisateur_creation' => 'nullable|string|max:25',
            'utilisateur_modification' => 'nullable|string|max:25',
            'horodatage_creation' => 'nullable|date_format:H:i:s',
            'horodatage_fin' => 'nullable|date_format:H:i:s',
            'horodatage_modification' => 'nullable|date_format:H:i:s',
            'code_modele' => 'nullable|string|max:8',
            'statut' => 'nullable|string|exists:statut_relance,code',
            'date_par_statut' => 'nullable|date',
            'actif' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $dossier->update([
            ...$request->except('actif'),
            'actif' => $request->boolean('actif', $dossier->actif),
        ]);

        return response()->json($dossier);
    }

    public function destroy($id)
    {
        $dossier = RelanceDossier::find($id);

        if (!$dossier) {
            return response()->json(['message' => 'Dossier non trouvé'], 404);
        }

        $dossier->delete();

        return response()->json(['message' => 'Dossier supprimé avec succès']);
    }

    public function getRelancesByClient($code_client)
    {
        $relances = RelanceDossier::where('code_client', $code_client)->get();
        return response()->json($relances);
    }

     public function updateStatus(Request $request, $ndr)
    {
        $dossier = RelanceDossier::where('numero_relance_dossier', $ndr)->first();

        if (!$dossier) {
        return response()->json([
            'success' => false,
            'message' => 'Dossier non trouvé'
        ], 404);
    }

        $validator = Validator::make($request->all(), [
        'status' => 'required|string|in:Ouvert,Cloture',
        ]);

        if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
        }

        $newStatut = strtoupper($request->input('status'));

        // Vérifie si le code existe dans la table statut_relance
        if (!\App\Models\StatutRelance::where('code', $newStatut)->exists()) {
        return response()->json([
            'success' => false,
            'message' => "Le statut $newStatut n'existe pas."
        ], 400);
    }

        $dossier->statut = $newStatut;
        $dossier->date_par_statut = now();
        $dossier->horodatage_modification = now();
        $dossier->utilisateur_modification = auth()->user()?->name ?? 'Système';
        $dossier->save();

        // Recharge la relation "statut" pour que le frontend voie les infos mises à jour
        $dossier->load('statut');

        return $this->show($dossier->numero_relance_dossier);
    }

}
