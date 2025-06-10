<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulletinSalaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'employe_id',
        'entreprise_id',
        'mois',
        'annee',
        'salaire_brut',
        'total_primes',
        'total_retenues',
        'salaire_net',
        'date_generation',
        'statut',
        'notes',
    ];

    protected $casts = [
        'salaire_brut' => 'decimal:2',
        'total_primes' => 'decimal:2',
        'total_retenues' => 'decimal:2',
        'salaire_net' => 'decimal:2',
        'date_generation' => 'date',
    ];

    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }
}
