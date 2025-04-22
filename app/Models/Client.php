<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'raison_sociale',
        'adresse',
        'ville',
        'pays',
        'telephone',
        'email',
        'responsable',
        'secteur_activite',
        'solde',
        'encours_autorise',
        'actif',
        'date_creation',
        'derniere_relance',
        'code_client',
    ];

    protected $casts = [
        'date_creation' => 'datetime:Y-m-d',
        'derniere_relance' => 'datetime:Y-m-d',
        'solde' => 'decimal:2',
        'encours_autorise' => 'decimal:2',
        'actif' => 'boolean',
    ];

    public function toArray()
    {
        $attributes = parent::toArray(); // Récupérer les attributs par défaut

        // Ajouter manuellement le code_client dans la réponse JSON
        $attributes['code_client'] = $this->code_client;

        return $attributes;
    }

    public function releves()
    {
        return $this->hasMany(Releve::class, 'code_client', 'code_client');
    }

    public function relance()
    {
        return $this->hasMany(RelanceDossier::class, 'code_client', 'code_client');
    }

    //public function historique()
    //{
      //  return $this->hasMany(HistoriqueRelance::class, 'code_client', 'code_client');
    //}
    //public function secteur()
    //{
    //    return $this->belongsTo(SecteurActivite::class, 'secteur_activite_id');
    //}
}
