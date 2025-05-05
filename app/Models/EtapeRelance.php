<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EtapeRelance extends Model
{
    use HasFactory;

    protected $table = 'etape_relances';

    protected $fillable = [
        'numero_relance',
        'numero_relance_dossier',
        'code_client',
        'code_sous_modele',
        'titre_sous_modele',
        'ordre',
        'statut_detail',
        'date_par_statut',
        'etat_relance_actif',
        'date_rappel',
        'nombre_jour_rappel',
        'methode_envoi',
        'executant_envoi',
        'date_creation_debut',
        'date_creation_fin',
        'etape_actif',
        'objet_relance_1',
        'objet_relance_2',
        'pdf_path'
    ];

    // Casts pour les dates
    protected $casts = [
        'date_par_statut' => 'date',
        'date_rappel' => 'date',
        'date_creation_debut' => 'date',
        'date_creation_fin' => 'date',
    ];

    // Relations
    public function relanceDossier()
    {
        return $this->belongsTo(RelanceDossier::class, 'numero_relance_dossier', 'numero_relance_dossier');
    }

    public function statutRelanceDetail()
    {
        return $this->belongsTo(StatutRelanceDetail::class, 'statut_detail', 'code');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'code_client', 'code_client');
    }

    public function eventRelances()
    {
        return $this->hasMany(EventRelance::class, 'numero_relance', 'numero_relance');
    }

    public function sousModele()
    {
        return $this->belongsTo(SousModele::class, 'code_sous_modele', 'code_sous_modele');
    }

    /**
     * Relation avec les situations de relance.
     * Une étape de relance peut avoir plusieurs situations.
     */
    public function situationRelances()
    {
        return $this->hasMany(SituationRelance::class, 'numero_etape_relance', 'numero_relance');
    }

    //public function creanceRelances()
    //{
    //    return $this->belongsToMany(
    //        CreanceRelance::class,
    //        'situation_relances',
    //        'numero_etape_relance',
    //        'ref_creance'
    //    )->withPivot(['date', 'debit', 'credit', 'valeur', 'observation', 'ordre']);
    //}

    //historiquerelation numero relance
    //sousmodelerelation code sous modele


    protected static function booted()
    {
    static::creating(function ($etape) {
        $yearSuffix = now()->format('y'); // ex: 25
        $prefix = 'ETR';

        // Cherche le dernier numéro existant
        $last = self::where('numero_relance', 'like', $prefix . $yearSuffix . '%')
                    ->orderByDesc('numero_relance')
                    ->first();

        if (!$last) {
            $nextNumber = 1;
        } else {
            $lastNumber = (int) substr($last->numero_relance, 5);
            $nextNumber = $lastNumber + 1;
        }

        $etape->numero_relance = $prefix . $yearSuffix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        });
    }
    
        public function downloadPdf($id)
    {
        $etape = EtapeRelance::findOrFail($id);

        if (!$etape->pdf_path || !\Storage::disk('public')->exists($etape->pdf_path)) {
            return response()->json(['error' => 'Fichier PDF introuvable.'], 404);
        }

        return response()->download(storage_path('app/public/' . $etape->pdf_path));
    }

}
