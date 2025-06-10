<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElementSalaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'nom',
        'montant',
        'employe_id',
        'recurrent',
        'description',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'recurrent' => 'boolean',
    ];

    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }
}
