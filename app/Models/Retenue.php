<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'montant',
        'employe_id',
        'mois',
        'annee',
        'type',
        'obligatoire',
        'description',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'obligatoire' => 'boolean',
    ];

    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }
}
