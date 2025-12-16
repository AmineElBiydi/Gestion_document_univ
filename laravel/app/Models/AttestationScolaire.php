<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttestationScolaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_id',
        'niveau',
        'filiere',
        'annee_universitaire',
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }
}
