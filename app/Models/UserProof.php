<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// class UserProof extends Model
// {
//     public function proofs()
// {
//     return $this->hasMany(Proof::class, 'user_id');
// }

// }
class UserProof extends Model
{
    protected $fillable = ['name', 'email', 'id_proof', 'id_proof_status', 'address_proof', 'address_proof_status', 'status'];

    public function proofs()
    {
        return $this->hasMany(Proof::class, 'user_id');
    }

    public function getIdProofAttribute()
    {
        return $this->proofs()->where('proof_type', 'id')->first();
    }

    public function getAddressProofAttribute()
    {
        return $this->proofs()->where('proof_type', 'address')->first();
    }

    public function getProofStatusAttribute()
    {
        if ($this->id_proof_status === 'Not Submitted' && $this->address_proof_status === 'Not Submitted') {
            return 'Not Submitted';
        } elseif ($this->id_proof_status === 'Rejected' || $this->address_proof_status === 'Rejected') {
            return 'Rejected';
        } elseif ($this->id_proof_status === 'Waiting for Approval' || $this->address_proof_status === 'Waiting for Approval') {
            return 'Waiting for Approval';
        } elseif ($this->id_proof_status === 'Approved' && $this->address_proof_status === 'Approved') {
            return 'Approved';
        }

        return 'Not Submitted';
    }
}


