<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'entreprise_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function entreprises()
    {
        return $this->belongsToMany(Entreprise::class, 'user_entreprise');
    }

    public function employe()
    {
        return $this->hasOne(Employe::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isResponsableEntreprise()
    {
        return $this->role === 'responsable_entreprise';
    }

    public function isEmploye()
    {
        return $this->role === 'employe';
    }

    public function hasAccessToEntreprise($entrepriseId)
    {
        if ($this->isAdmin()) {
            return true;
        }
        
        if ($this->isResponsableEntreprise() && $this->entreprise_id == $entrepriseId) {
            return true;
        }
        
        return $this->entreprises()->where('entreprise_id', $entrepriseId)->exists();
    }

    public function getAccessibleEntreprises()
    {
        if ($this->isAdmin()) {
            return Entreprise::all();
        }
        
        if ($this->isResponsableEntreprise() && $this->entreprise_id) {
            return Entreprise::where('id', $this->entreprise_id)->get();
        }
        
        return $this->entreprises;
    }
}
