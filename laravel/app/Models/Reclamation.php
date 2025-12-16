<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_id',
        'etudiant_id',
        'type',
        'description',
        'status',
        'piece_jointe_path',
        'reponse',
        'traite_par_admin_id',
        'date_traitement',
    ];

    protected $casts = [
        'date_traitement' => 'datetime',
    ];

    /**
     * Relation avec l'étudiant
     */
    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    /**
     * Relation avec la demande concernée
     */
    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }

    /**
     * Relation avec l'admin qui a traité la réclamation
     */
    public function traiteParAdmin()
    {
        return $this->belongsTo(Admin::class, 'traite_par_admin_id');
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope pour filtrer par type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
