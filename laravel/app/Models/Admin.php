<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'identifiant',
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'est_actif',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'est_actif' => 'boolean',
        ];
    }

    /**
     * Relation avec les demandes traitées
     */
    public function demandesTraitees()
    {
        return $this->hasMany(Demande::class, 'traite_par_admin_id');
    }

    /**
     * Relation avec les réclamations traitées
     */
    public function reclamationsTraitees()
    {
        return $this->hasMany(Reclamation::class, 'traite_par_admin_id');
    }
}
