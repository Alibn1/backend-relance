<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens; // Remplace Sanctum par Passport

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Les attributs assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 
        'email',
        'password',
        'role',
    ];

    /**
     * Les attributs à cacher lors de la sérialisation.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs à caster.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relations
     */

    // Toutes les relances créées par l'utilisateur (agent)
    public function relances()
    {
        return $this->hasMany(\App\Models\Relance::class, 'user_id');
    }
    
    public function isAgent()
    {
        return strtolower($this->role) === 'agent';
    }
    
    public function isResponsable()
    {
        return strtolower($this->role) === 'responsable';
    }
}
