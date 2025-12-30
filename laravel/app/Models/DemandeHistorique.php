<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeHistorique extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_id',
        'user_id',
        'action',
        'details',
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }

    // Optionally link to User or Admin if needed
}
