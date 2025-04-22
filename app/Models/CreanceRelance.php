<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreanceRelance extends Model
{
    use HasFactory;

    protected $table = 'creance_relances';

    protected $fillable = [
        'ref_creance',
        'titre_creance',
        'ordre_creance',
        'date_creance',
        'actif',
    ];

    // Cast pour les dates et autres champs nécessaires
    protected $casts = [
        'date_creance' => 'date',
        'actif' => 'boolean',
    ];

    public function situationRelances()
    {
        return $this->hasMany(SituationRelance::class, 'ref_creance', 'ref_creance');
    }

    /**
     * Relation avec les événements de relance.
     * Une créance de relance peut être associée à plusieurs événements.
     */
    public function etapeRelance()
    {
        return $this->belongsToMany(
        EtapeRelance::class, // Modèle à travers lequel nous accédons
        'situation_relances', // Clé étrangère dans EtapeRelance
        'ref_creance', // Clé primaire dans CreanceRelance
        'numero_etape_relance'
        )->using(SituationRelance::class)
        ->withPivot(['date', 'debit', 'credit', 'valeur', 'observation', 'ordre']);
    }
    
    public function relance()
    {
        return $this ->hasManyThrough(
        RelanceDossier::class,
        EtapeRelance::class, 
        'numero_relance_dossier', //cle etrangere sur etapeRelance
        'numero_relance_dossier', //sur relance
        'ref_creance', 
        'numero_relance'
        )->distinct();
    }


    public static function generateUniqueRFC()
    {
        $prefix = 'CR'; // Préfixe du numéro
        $year = date('Y'); // Année actuelle
        $newNumber = 0; // Initialisation du numéro unique

        do {
            // Récupérer le dernier numéro de créance (en fonction de l'année et de l'incrémentation)
            $lastCreance = self::where('ref_creance', 'like', "{$prefix}-{$year}-%")
                               ->latest('created_at')
                               ->first(); 

            // Si aucun enregistrement précédent, le numéro commence à 1
            $lastNumber = $lastCreance ? (int) substr($lastCreance->ref_creance, -5) : 0;
            $newNumber = $lastNumber + 1;

            // Générer le nouveau numéro avec 5 chiffres
            $newRFC = "{$prefix}-{$year}-" . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        } while (self::where('ref_creance', $newRFC)->exists()); // Vérifier si ce numéro existe déjà

        return $newRFC;
    }

    /**
     * Avant de sauvegarder la créance, générer le numéro de créance
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($creanceRelance) {
            // Si ref_creance n'est pas défini, générer un numéro unique
            if (!$creanceRelance->ref_creance) {
                $creanceRelance->ref_creance = self::generateUniqueRFC();
            }
        });
    }
}
