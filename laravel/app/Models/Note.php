<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'inscription_id',
        'module_niveau_id',
        'type_session',
        'note',
        'est_valide',
        'date_saisie',
    ];

    protected $casts = [
        'note' => 'decimal:2',
        'est_valide' => 'boolean',
        'date_saisie' => 'datetime',
    ];

    /**
     * Relation avec l'inscription
     */
    public function inscription(): BelongsTo
    {
        return $this->belongsTo(Inscription::class);
    }

    /**
     * Relation avec le module_niveau
     */
    public function moduleNiveau(): BelongsTo
    {
        return $this->belongsTo(ModuleNiveau::class, 'module_niveau_id');
    }
}

