<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'apogee',
        'cin',
        'email',
        'status',
        'filiere',
        'niveau',
    ];

    /**
     * Relation avec les demandes
     */
    public function demandes()
    {
        return $this->hasMany(Demande::class);
    }
}
