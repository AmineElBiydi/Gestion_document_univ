<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleveNotes extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_id',
        'decision_annee_id',
    ];

    /**
     * Relation avec la demande
     */
    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }

    /**
     * Relation avec la décision d'année
     */
    public function decisionAnnee()
    {
        return $this->belongsTo(DecisionAnnee::class);
    }
}
