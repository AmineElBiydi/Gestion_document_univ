<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfesseurFiliere extends Model
{
    use HasFactory;

    protected $table = 'professeurs_filieres';

    protected $fillable = [
        'professeur_id',
        'filiere_id',
        'role',
    ];

    /**
     * Relation avec le professeur
     */
    public function professeur(): BelongsTo
    {
        return $this->belongsTo(Professeur::class);
    }

    /**
     * Relation avec la filière
     */
    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class);
    }
}

