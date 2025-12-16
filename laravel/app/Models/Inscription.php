<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'etudiant_id',
        'annee_id',
        'filiere_id',
        'niveau_id',
        'date_inscription',
        'statut',
    ];

    protected $casts = [
        'date_inscription' => 'date',
    ];

    /**
     * Relation avec l'étudiant
     */
    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }

    /**
     * Relation avec l'année universitaire
     */
    public function anneeUniversitaire(): BelongsTo
    {
        return $this->belongsTo(AnneeUniversitaire::class, 'annee_id');
    }

    /**
     * Relation avec la filière
     */
    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class);
    }

    /**
     * Relation avec le niveau
     */
    public function niveau(): BelongsTo
    {
        return $this->belongsTo(Niveau::class);
    }

    /**
     * Relation avec les décisions d'année
     */
    public function decisionsAnnee(): HasMany
    {
        return $this->hasMany(DecisionAnnee::class);
    }

    /**
     * Relation avec les notes
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Relation avec les demandes
     */
    public function demandes(): HasMany
    {
        return $this->hasMany(Demande::class);
    }
}

