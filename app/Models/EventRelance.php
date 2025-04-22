<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRelance extends Model
{
    use HasFactory;

    protected $table = 'event_relances';

    // Définition des colonnes pouvant être remplies par l'utilisateur
    protected $fillable = [
        'numero_evenement',
        'numero_relance',
        'date_evenement',
        'statut',
        'date_promesse',
        'contact',
        'user_creation',
        'observation',
        'actif',
        'code_client',
        'solde_a_relancer',
        'date_prochaine_action',
    ];

    // Cast pour les dates et autres champs nécessaires
    protected $casts = [
        'date_evenement' => 'date',
        'date_promesse' => 'date',
        'date_prochaine_action' => 'date',
    ];

    /**
     * Relation avec le modèle EtapeRelance.
     * Chaque événement de relance appartient à une étape de relance.
     */
    public function etapeRelance()
    {
        return $this->belongsTo(EtapeRelance::class, 'numero_relance', 'numero_relance');
    }

    /**
     * Relation avec le modèle StatutRelanceDetail.
     * Chaque événement de relance a un statut détaillé.
     */
    public function statutRelanceDetail()
    {
        return $this->belongsTo(StatutRelanceDetail::class, 'statut', 'code');
    }

    /**
     * Relation avec le modèle Client.
     * Chaque événement de relance appartient à un client.
     */
}
