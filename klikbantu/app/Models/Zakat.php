<?php
// app/Models/Zakat.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zakat extends Model
{
    protected $table = 'zakats';
    protected $fillable = [
        'id_user', 
        'nama_pembayar', 
        'jenis_zakat', 
        'nominal', 
        'no_hp', 
        'status_pembayaran', 
        'snap_token'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}