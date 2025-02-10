<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProof extends Model
{
    public function proofs()
{
    return $this->hasMany(Proof::class, 'user_id');
}

}
