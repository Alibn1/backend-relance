<?php

namespace App\Http\Controllers;

use App\Models\EtapeRelance;
use App\Models\RelanceDossier;
use App\Services\SousModelePDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EtapeRelanceController extends Controller
{
    protected $pdfService;

    public function __construct(SousModelePDFService $pdfService)
    {
        $this->middleware('auth:api');
        $this->pdfService = $pdfService;
    }

    public function getByClient($code_client)
    {
        return EtapeRelance::where('code_client', $code_client)->get();
    }


    public function index()
    {
        $etapes = EtapeRelance::with([
            'relanceDossier', 'statutRelanceDetail', 'client', 'eventRelances', 'situationRelances', 'sousModele', 'releves'
        ])->get();

        return response()->json($etapes);
    }

    public function store(Request $request, $ndr)
    {
        $relance = RelanceDossier::with('client')->where('numero_relance_dossier', $ndr)->first();

        if (!$relance) {
            return response()->json(['message' => "Relance $ndr introuvable."], 404);
        }

        $data = $request->all();

        DB::beginTransaction();
        try {
            $etape = EtapeRelance::create([
                'numero_relance_dossier' => $ndr,
                'code_client' => $relance->code_client,
                'code_sous_modele' => $data['code_sous_modele'] ?? null,
                'titre_sous_modele' => $data['titre_sous_modele'] ?? null,
                'ordre' => $data['ordre'] ?? null,
                'statut_detail' => $data['statut_detail'] ?? 'BROUILLON',
                'date_rappel' => $data['date_rappel'] ?? null,
                'nombre_jour_rappel' => $data['nb_jours_rappel'] ?? 7,
                'methode_envoi' => $data['methode_envoi'] ?? null,
                'executant_envoi' => Auth::user()->name ?? 'System',
                'objet_relance_1' => $data['objet_relance_1'] ?? null,
                'objet_relance_2' => $data['objet_relance_2'] ?? null,
                'date_creation_debut' => now(),
                'etat_relance_actif' => 'O',
                'etape_actif' => 'O'
            ]);

            // 🟢 Attacher les relevés si fournis
        if (!empty($data['code_releves']) && is_array($data['code_releves'])) {
            $etape->releves()->syncWithoutDetaching($data['code_releves']);
        }

        // 🟣 Générer PDF si possible
        if (!empty($etape->code_sous_modele)) {
            $client = $etape->client;
            $releve = $client->releves()->latest()->first();
            $sousModele = $etape->sousModele;

            if ($client && $releve && $sousModele) {
                $result = $this->pdfService->generatePDF($client, $releve, $sousModele);
                $etape->pdf_path = $result['path'];
                $etape->save();
            }
        }

            DB::commit();
            return response()->json(['message' => 'Étape créée avec succès.', 'data' => $etape], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur création étape :', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Erreur lors de la création.', 'exception' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $etape = EtapeRelance::with([
            'relanceDossier', 'statutRelanceDetail', 'client', 'eventRelances', 'situationRelances', 'sousModele', 'releves'
        ])->findOrFail($id);

        return response()->json($etape);
    }

    public function update(Request $request, $id)
    {
        $etape = EtapeRelance::findOrFail($id);

        $etape->update($request->only([
            'titre_sous_modele', 'ordre', 'statut_detail', 'date_par_statut',
            'etat_relance_actif', 'date_rappel', 'nombre_jour_rappel', 'methode_envoi',
            'executant_envoi', 'date_creation_debut', 'date_creation_fin',
            'etape_actif', 'objet_relance_1', 'objet_relance_2'
        ]));

        return response()->json(['message' => 'Étape mise à jour avec succès.', 'data' => $etape]);
    }

    public function destroy($id)
    {
        $etape = EtapeRelance::findOrFail($id);
        $etape->delete();

        return response()->json(['message' => 'Étape supprimée avec succès.'], 204);
    }

    public function scopeActives($query)
    {
        return $query->where('etape_actif', '1');
    }
}
