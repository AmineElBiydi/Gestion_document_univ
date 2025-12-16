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
        'encadrant_pedagogique',
        'fonction_encadrant',
        'sujet',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }
}
