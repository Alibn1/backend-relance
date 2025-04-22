<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    /**
     * Afficher la liste des clients.
     */
    public function index()
    {
        return response()->json(Client::all());
    }

    /**
     * Créer un nouveau client.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'raison_sociale' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clients,email',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'pays' => 'nullable|string|max:100',
            'responsable' => 'nullable|string|max:255',
            'secteur_activite' => 'nullable|string|max:255',
            'solde' => 'nullable|numeric',
            'encours_autorise' => 'nullable|numeric',
            'actif' => 'boolean',
            'date_creation' => 'nullable|date',
            'derniere_relance' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $client = Client::create($request->all());

        return response()->json($client, 201);
    }

    /**
     * Afficher un client spécifique.
     */
    public function show($id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['message' => 'Client non trouvé'], 404);
        }

        //return response()->json($client);
        return response()->json($client->toArray());
    }

    /**
     * Mettre à jour un client existant.
     */
    public function update(Request $request, $id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['message' => 'Client non trouvé'], 404);
        }

        $validator = Validator::make($request->all(), [
            'raison_sociale' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|unique:clients,email,' . $client->id,
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'pays' => 'nullable|string|max:100',
            'responsable' => 'nullable|string|max:255',
            'secteur_activite' => 'nullable|string|max:255',
            'solde' => 'nullable|numeric',
            'encours_autorise' => 'nullable|numeric',
            'actif' => 'boolean',
            'date_creation' => 'nullable|date',
            'derniere_relance' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $client->update($request->all());

        return response()->json($client);
    }

    /**
     * Supprimer un client.
     */
    public function destroy($id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['message' => 'Client non trouvé'], 404);
        }

        $client->delete();

        return response()->json(['message' => 'Client supprimé avec succès']);
    }
}
