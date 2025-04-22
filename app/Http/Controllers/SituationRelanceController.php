<?php

namespace App\Http\Controllers;

use App\Models\SituationRelance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SituationRelanceController extends Controller
{
    /**
     * Lister toutes les situations de relance.
     */
    public function index()
    {
        return response()->json(SituationRelance::with(['etapeRelance', 'creanceRelance'])->get());
    }

    /**
     * Créer une nouvelle situation de relance.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero_etape_relance' => 'required|exists:etape_relances,numero_relance',
            'ref_creance' => 'required|exists:creance_relances,ref_creance',
            'date' => 'nullable|date',
            'debit' => 'nullable|numeric',
            'credit' => 'nullable|numeric',
            'valeur' => 'nullable|numeric',
            'observation' => 'nullable|string|max:30',
            'ordre' => 'nullable|string|max:2',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $situation = SituationRelance::create($request->all());

        return response()->json($situation, 201);
    }

    /**
     * Afficher une situation de relance.
     */
    public function show($numero_etape_relance, $ref_creance)
    {
        $situation = SituationRelance::where('numero_etape_relance', $numero_etape_relance)
                                     ->where('ref_creance', $ref_creance)
                                     ->with(['etapeRelance', 'creanceRelance'])
                                     ->first();

        if (!$situation) {
            return response()->json(['message' => 'Situation non trouvée'], 404);
        }

        return response()->json($situation);
    }

    /**
     * Mettre à jour une situation de relance.
     */
    public function update(Request $request, $numero_etape_relance, $ref_creance)
    {
        $situation = SituationRelance::where('numero_etape_relance', $numero_etape_relance)
                                     ->where('ref_creance', $ref_creance)
                                     ->first();

        if (!$situation) {
            return response()->json(['message' => 'Situation non trouvée'], 404);
        }

        $validator = Validator::make($request->all(), [
            'date' => 'nullable|date',
            'debit' => 'nullable|numeric',
            'credit' => 'nullable|numeric',
            'valeur' => 'nullable|numeric',
            'observation' => 'nullable|string|max:30',
            'ordre' => 'nullable|string|max:2',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $situation->update($request->all());

        return response()->json($situation);
    }

    /**
     * Supprimer une situation de relance.
     */
    public function destroy($numero_etape_relance, $ref_creance)
    {
        $situation = SituationRelance::where('numero_etape_relance', $numero_etape_relance)
                                     ->where('ref_creance', $ref_creance)
                                     ->first();

        if (!$situation) {
            return response()->json(['message' => 'Situation non trouvée'], 404);
        }

        $situation->delete();

        return response()->json(['message' => 'Situation supprimée avec succès']);
    }
}
