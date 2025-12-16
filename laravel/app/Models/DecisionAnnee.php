<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DecisionAnnee extends Model
{
    use HasFactory;

    protected $table = 'decisions_annee';

    protected $fillable = [
        'inscription_id',
        'type_session',
        'moyenne_annuelle',
        'credits_valides',
        'credits_totaux',
        'mention',
        'decision',
        'date_decision',
    ];

    protected $casts = [
        'moyenne_annuelle' => 'decimal:2',
        'date_decision' => 'date',
    ];

    /**
     * Relation avec l'inscription
     */
    public function inscription(): BelongsTo
    {
        return $this->belongsTo(Inscription::class);
    }

    /**
     * Relation avec les attestations de réussite
     */
    public function attestationsReussite(): HasMany
    {
        return $this->hasMany(AttestationReussite::class);
    }

    /**
     * Relation avec les relevés de notes
     */
    public function relevesNotes(): HasMany
    {
        return $this->hasMany(ReleveNotes::class);
    }
}

