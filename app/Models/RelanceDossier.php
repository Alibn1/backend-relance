<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RelanceDossier extends Model
{
    protected $table = 'relance_dossiers';

    protected $fillable = [
        'numero_relance_dossier',
        'date_relance_dossier',
        'code_client',
        'contact_interlocuteur',
        'utilisateur_creation',
        'utilisateur_modification',
        'horodatage_creation',
        'horodatage_fin',
        'horodatage_modification',
        'code_modele',
        'statut',
        'date_par_statut',
        'actif',
    ];

    protected $casts = [
        'date_relance_dossier' => 'date',
        'date_par_statut' => 'date',
        'horodatage_creation' => 'datetime:H:i:s',
        'horodatage_fin' => 'datetime:H:i:s',
        'horodatage_modification' => 'datetime:H:i:s',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'code_client', 'code_client');
    }

    public function statut()
    {
        return $this->belongsTo(StatutRelance::class, 'statut', 'code');
    }



    public function etapeRelances()
    {
        return $this->hasMany(EtapeRelance::class, 'numero_relance_dossier', 'numero_relance_dossier');
    }

    /**
     * Relation avec les créances de relance.
     * Un dossier de relance peut avoir plusieurs créances.
     */
    //public function creanceRelances()
    //{
    //    return $this->hasMany(CreanceRelance::class, 'ref_creance', 'ref_creance');
    //}

    //public function historique(){
    //    return $this->hasMany(HistoriqueRelanceDossier::class, 'numero_relance_dossier', 'numero_relance_dossier');
    //}
    //public function modele(){
    //    return $this->hasMany(HistoriqueRelanceDossier::class, 'code_modele', 'code_modele');
    //}

    public static function activeForClient($ClientId)
    {
        return self::whereRaw('code_client = ?', [$ClientId])
            ->whereRaw('statut = 1', ['EN_COURS'])
            ->get();
    }

    protected static function booted()
    {
        static::creating(function ($dossier) {
            // Année en cours
            $year = now()->year; 
            $yearSuffix = now()->format('y'); // Ex: 25 pour 2025
            $prefix = 'REL'; // Préfixe pour RelanceDossier

            // Chercher le dernier numéro généré dans la table pour ce modèle
            $lastNumero = DB::table('relance_dossiers')
                ->where('numero_relance_dossier', 'like', "$prefix$yearSuffix%") // Filtrer par année et préfixe
                ->orderByDesc('numero_relance_dossier')
                ->value('numero_relance_dossier');

            // Si aucun numéro n'existe, commencer à REL25001
            if (!$lastNumero) {
                $val = $prefix . $yearSuffix . '001';
            } else {
                // Extraire le dernier numéro et incrémenter
                $lastNumber = (int) substr($lastNumero, 5); // Ex: 001
                $nextNumber = $lastNumber + 1;
                $val = $prefix . $yearSuffix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }

            // Assigner le numéro généré au modèle
            $dossier->numero_relance_dossier = $val;
        });
    }
}
