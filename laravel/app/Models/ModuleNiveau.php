<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModuleNiveau extends Model
{
    use HasFactory;

    protected $table = 'modules_niveau';

    protected $fillable = [
        'module_id',
        'filiere_id',
        'niveau_id',
        'coefficient',
        'est_obligatoire',
    ];

    protected $casts = [
        'coefficient' => 'decimal:2',
        'est_obligatoire' => 'boolean',
    ];

    /**
     * Relation avec le module
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
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
     * Relation avec les notes
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'module_niveau_id');
    }
}

