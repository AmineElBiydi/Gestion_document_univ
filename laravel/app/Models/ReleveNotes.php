<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleveNotes extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_id',
        'semestre',
        'annee_universitaire',
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }
}
