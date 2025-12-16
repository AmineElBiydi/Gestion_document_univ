<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Filiere extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_filiere',
        'nom_filiere',
        'cycle',
        'description',
        'est_active',
    ];

    protected $casts = [
        'est_active' => 'boolean',
    ];

    /**
     * Relation avec les inscriptions
     */
    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
    }

    /**
     * Relation avec les professeurs (many-to-many)
     */
    public function professeurs(): BelongsToMany
    {
        return $this->belongsToMany(Professeur::class, 'professeurs_filieres')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Relation avec les modules (through modules_niveau)
     */
    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'modules_niveau')
            ->withPivot('niveau_id', 'coefficient', 'est_obligatoire')
            ->withTimestamps();
    }
}

