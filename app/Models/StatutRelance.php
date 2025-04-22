<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatutRelance extends Model
{
    use HasFactory;

    protected $table = 'statut_relance';
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

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(StatutRelanceDetail::class, 'statut', 'code');
    }

    public function relances(): HasMany
    {
        return $this->hasMany(RelanceDossier::class, 'statut', 'code');
    }
}
