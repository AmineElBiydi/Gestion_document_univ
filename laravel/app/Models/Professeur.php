<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Professeur extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'email',
        'telephone',
        'specialite',
        'grade',
        'est_actif',
    ];

    protected $casts = [
        'est_actif' => 'boolean',
    ];

    /**
     * Relation avec les filières (many-to-many)
     */
    public function filieres(): BelongsToMany
    {
        return $this->belongsToMany(Filiere::class, 'professeurs_filieres')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Relation avec les conventions de stage (comme encadrant pédagogique)
     */
    public function conventionsStage(): HasMany
    {
        return $this->hasMany(ConventionStage::class, 'encadrant_pedagogique_id');
    }
}

