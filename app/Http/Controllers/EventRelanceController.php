<?php

namespace App\Http\Controllers;

use App\Models\EventRelance;
use Illuminate\Http\Request;

class EventRelanceController extends Controller
{
    /**
     * Lister tous les événements de relance avec leurs relations.
     */
    public function index()
    {
        $events = EventRelance::with(['etapeRelance', 'statutRelanceDetail'])->get();
        return response()->json($events);
    }

    /**
     * Créer un nouvel événement de relance.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_evenement' => 'required|string|max:30|unique:event_relances',
            'numero_relance' => 'required|string|exists:etape_relances,numero_relance',
            'date_evenement' => 'nullable|date',
            'statut' => 'nullable|string|exists:statut_relance_detail,code',
            'date_promesse' => 'nullable|date',
            'contact' => 'nullable|string|max:30',
            'user_creation' => 'nullable|string|max:30',
            'observation' => 'nullable|string|max:50',
            'actif' => 'nullable|string|in:0,1',
            'code_client' => 'nullable|string|max:6',
            'solde_a_relancer' => 'nullable|string|max:20',
            'date_prochaine_action' => 'nullable|date',
        ]);

        $event = EventRelance::create($validated);

        return response()->json($event, 201);
    }

    /**
     * Afficher un événement spécifique.
     */
    public function show($id)
    {
        $event = EventRelance::with(['etapeRelance', 'statutRelanceDetail'])->findOrFail($id);
        return response()->json($event);
    }

    /**
     * Mettre à jour un événement de relance.
     */
    public function update(Request $request, $id)
    {
        $event = EventRelance::findOrFail($id);

        $validated = $request->validate([
            'numero_relance' => 'sometimes|required|string|exists:etape_relances,numero_relance',
            'date_evenement' => 'nullable|date',
            'statut' => 'nullable|string|exists:statut_relance_detail,code',
            'date_promesse' => 'nullable|date',
            'contact' => 'nullable|string|max:30',
            'user_creation' => 'nullable|string|max:30',
            'observation' => 'nullable|string|max:50',
            'actif' => 'nullable|string|in:0,1',
            'code_client' => 'nullable|string|max:6',
            'solde_a_relancer' => 'nullable|string|max:20',
            'date_prochaine_action' => 'nullable|date',
        ]);

        $event->update($validated);

        return response()->json($event);
    }

    /**
     * Supprimer un événement.
     */
    public function destroy($id)
    {
        $event = EventRelance::findOrFail($id);
        $event->delete();

        return response()->json(null, 204);
    }
}
