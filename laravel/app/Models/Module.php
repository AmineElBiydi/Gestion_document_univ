<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_module',
        'nom_module',
        'credits',
        'description',
    ];

    /**
     * Relation avec les filières et niveaux (through modules_niveau)
     */
    public function filieres(): BelongsToMany
    {
        return $this->belongsToMany(Filiere::class, 'modules_niveau')
            ->withPivot('niveau_id', 'coefficient', 'est_obligatoire')
            ->withTimestamps();
    }

    /**
     * Relation avec les niveaux (through modules_niveau)
     */
    public function niveaux(): BelongsToMany
    {
        return $this->belongsToMany(Niveau::class, 'modules_niveau')
            ->withPivot('filiere_id', 'coefficient', 'est_obligatoire')
            ->withTimestamps();
    }
}

