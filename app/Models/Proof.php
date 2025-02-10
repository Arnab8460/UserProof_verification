<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proof extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'file_path', 'status'];

    public function user()
    {
        return $this->belongsTo(UserProof::class, 'user_id');
    }
}
