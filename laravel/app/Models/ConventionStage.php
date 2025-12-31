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
        'secteur_entreprise',
        'telephone_entreprise',
        'email_entreprise',
        'adresse_entreprise',
        'ville_entreprise',
        'representant_entreprise',
        'fonction_representant',
        'encadrant_entreprise',
        'fonction_encadrant',
        'telephone_encadrant',
        'email_encadrant',
        'encadrant_pedagogique_id',
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
