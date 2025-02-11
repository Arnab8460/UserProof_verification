<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proof extends Model
{
    protected $fillable = ['user_id', 'proof_type', 'file_path', 'status'];

    public function user()
    {
        return $this->belongsTo(UserProof::class, 'user_id');
    }
}
