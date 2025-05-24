<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Releve extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'releves';

    protected $fillable = [
        'code_releve',
        'code_client',
        'date_releve',
        'solde_initiale',
        'solde_finale',
        'statut',
        'commentaire',
        'created_by',
    ];

    // Casts pour les décimaux
    protected $casts = [
        'solde_initiale' => 'decimal:2',
        'solde_finale' => 'decimal:2',
        'date_releve' => 'date',
    ];

    // Relation avec le modèle Client
    public function client()
    {
        return $this->belongsTo(Client::class, 'code_client', 'code_client');
    }

    public function etapes()
    {
        return $this->belongsToMany(
            EtapeRelance::class,
            'etape_releve',
            'code_releve',       // colonne de la table pivot qui pointe vers Releve
            'numero_relance',    // colonne qui pointe vers EtapeRelance
            'code_releve',       // clé locale de Releve
            'numero_relance'     // clé locale de EtapeRelance
        );
    }



    //public function relance()
    //{
    //    return $this->belongsTo(HistoriqueRelance::class, 'code_releve', 'code_releve');
    //}
    
     /**
     * Génération manuelle d’un code_releve si besoin
     */
    public static function generateCodeReleve()
    {
        $last = self::where('code_releve', 'LIKE', 'REL%')
                    ->orderByDesc('id')
                    ->first();

        $lastNumber = 25000;

        if ($last && preg_match('/^REL(\d+)$/', $last->code_releve, $matches)) {
            $lastNumber = (int) $matches[1];
        }

        return 'REL' . ($lastNumber + 1);
    }

    /**
     * Hook pour générer le code côté Laravel (optionnel si trigger existe)
     */
    protected static function booted()
    {
        static::creating(function ($releve) {
            if (!$releve->code_releve) {
                $releve->code_releve = self::generateCodeReleve();
            }
        });
    }

}
