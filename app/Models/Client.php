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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($client) {
            if (empty($client->code_client)) {
                $lastClient = self::where('code_client', 'like', 'CLT%')
                                ->orderByDesc('id')
                                ->first();

                $nextNumber = $lastClient ? ((int)substr($lastClient->code_client, 3)) + 1 : 1;
                $client->code_client = 'CLT' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    public function toArray()
    {
        $attributes = parent::toArray();
        $attributes['code_client'] = $this->code_client;
        return $attributes;
    }

    public function releves()
    {
        return $this->hasMany(Releve::class, 'code_client', 'code_client');
    }

    public function relances()
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
