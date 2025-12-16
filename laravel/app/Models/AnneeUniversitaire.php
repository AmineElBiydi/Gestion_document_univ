<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnneeUniversitaire extends Model
{
    use HasFactory;

    protected $table = 'annees_universitaires';

    protected $fillable = [
        'libelle',
        'date_debut',
        'date_fin',
        'est_active',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'est_active' => 'boolean',
    ];

    /**
     * Relation avec les inscriptions
     */
    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class, 'annee_id');
    }
}

