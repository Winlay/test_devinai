<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'ninea',
        'adresse',
        'email',
        'telephone',
        'secteur_activite',
        'date_creation',
        'active',
    ];

    protected $casts = [
        'date_creation' => 'date',
        'active' => 'boolean',
    ];

    public function employes()
    {
        return $this->hasMany(Employe::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function bulletinSalaires()
    {
        return $this->hasMany(BulletinSalaire::class);
    }
}
