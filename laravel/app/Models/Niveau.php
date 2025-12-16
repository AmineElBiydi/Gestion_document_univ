<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Niveau extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_niveau',
        'libelle',
        'ordre',
    ];

    /**
     * Relation avec les inscriptions
     */
    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
    }

    /**
     * Relation avec les modules (through modules_niveau)
     */
    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'modules_niveau')
            ->withPivot('filiere_id', 'coefficient', 'est_obligatoire')
            ->withTimestamps();
    }
}

