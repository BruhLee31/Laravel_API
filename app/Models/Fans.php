<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fans extends Model
{
    use HasFactory;

    protected $fillable = ['nama_fans'];

    public function klub()
    {
        return $this->belongsToMany(Klub::class, 'fans_klub', 'id_fans', 'id_klub');
    }
}
