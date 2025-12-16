<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConventionStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_id',
        'date_debut',
        'date_fin',
        'entreprise',
        'adresse_entreprise',
        'email_encadrant',
        'telephone_encadrant',
        'encadrant_entreprise',
        'encadrant_pedagogique_id',
        'fonction_encadrant',
        'sujet',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    /**
     * Relation avec la demande
     */
    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }

    /**
     * Relation avec l'encadrant pédagogique (professeur)
     */
    public function encadrantPedagogique()
    {
        return $this->belongsTo(Professeur::class, 'encadrant_pedagogique_id');
    }
}
