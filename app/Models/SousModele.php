<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SousModele extends Model
{
    protected $fillable = [
        'code_sous_modele',
        'titre',
        'texte',
    ];

    protected $casts = [
        'texte' => 'array'
    ];

    public function etapes()
    {
        return $this->hasMany(EtapeRelance::class, 'code_sous_modele', 'code_sous_modele');
    }
}
