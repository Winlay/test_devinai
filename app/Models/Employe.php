<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'matricule',
        'date_entree',
        'entreprise_id',
        'salaire_base',
        'statut',
        'poste',
        'departement',
        'user_id',
    ];

    protected $casts = [
        'date_entree' => 'date',
        'salaire_base' => 'decimal:2',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function elementSalaires()
    {
        return $this->hasMany(ElementSalaire::class);
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function retenues()
    {
        return $this->hasMany(Retenue::class);
    }

    public function bulletinSalaires()
    {
        return $this->hasMany(BulletinSalaire::class);
    }

    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }
}
