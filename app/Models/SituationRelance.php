<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SituationRelance extends Model
{
    use HasFactory;

    protected $table = 'situation_relances';

    // Définition des colonnes pouvant être remplies par l'utilisateur
    protected $fillable = [
        'numero_etape_relance',
        'ref_creance',
        'date',
        'debit',
        'credit',
        'valeur',
        'observation',
        'ordre',
    ];

    // Cast pour les dates et autres champs nécessaires
    protected $casts = [
        'date' => 'date',
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
        'valeur' => 'decimal:2',
    ];

    /**
     * Relation avec le modèle EtapeRelance.
     * Chaque situation de relance appartient à une étape de relance.
     */
    public function etapeRelance()
    {
        return $this->belongsTo(EtapeRelance::class, 'numero_etape_relance', 'numero_relance');
    }

    /**
     * Relation avec le modèle CreanceRelance.
     * Chaque situation de relance appartient à une créance de relance.
     */
    public function creanceRelance()
    {
        return $this->belongsTo(CreanceRelance::class, 'ref_creance', 'ref_creance');
    }

    public function statutRelanceDetail()
    {
        return $this->through('etape')->has('statutRelanceDetail');
    }
}
