<?php

namespace App\Http\Controllers;

use App\Models\RelanceDossier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RelanceDossierController extends Controller
{
    public function index()
    {
        return response()->json(RelanceDossier::with(['client', 'statut', 'etapeRelances', 'creanceRelances'])->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero_relance_dossier' => 'required|string|max:8|unique:relance_dossiers,numero_relance_dossier',
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
        $dossier = RelanceDossier::with(['client', 'statut', 'etapeRelances', 'creanceRelances'])->find($id);

        if (!$dossier) {
            return response()->json(['message' => 'Dossier non trouvé'], 404);
        }

        return response()->json($dossier);
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
}
