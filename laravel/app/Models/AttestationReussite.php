<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttestationReussite extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_id',
        'filiere',
        'annee_universitaire',
        'cycle',
        'session',
        'type_releve',
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }
}
