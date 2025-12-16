<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Etudiant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'apogee',
        'cin',
        'email',
        'date_naissance',
        'lieu_naissance',
        'telephone',
        'adresse',
        'status',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    /**
     * Relation avec les demandes
     */
    public function demandes()
    {
        return $this->hasMany(Demande::class);
    }

    /**
     * Relation avec les réclamations
     */
    public function reclamations()
    {
        return $this->hasMany(Reclamation::class);
    }

    /**
     * Relation avec les inscriptions
     */
    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
    }
}
