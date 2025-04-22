<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatutRelanceDetail extends Model
{
    protected $table = 'statut_relance_detail';
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'libelle',
        'couleur',
        'champ_interface',
        'actif',
    ];

    public function statutprincipal()
    {
        return $this->belongsTo(StatutRelance::class, 'statut_detail', 'code');
    }

    /**
     * Relation avec les événements de relance.
     * Un statut de relance peut être associé à plusieurs événements de relance.
     */
    public function etapes()
    {
        return $this->hasMany(EtapeRelance::class, 'statut_detail', 'code');
    }
}
