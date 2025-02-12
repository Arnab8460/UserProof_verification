<?php

namespace App\Http\Controllers;
use App\Models\UserProof;
use App\Models\Proof;
use App\Models\User;
use Illuminate\Support\Facades\Storage;


use Illuminate\Http\Request;

class UserProofController extends Controller
{
    
    public function index(Request $request)
    {
        $query = UserProof::query();

        $query->orderByRaw("FIELD(status, 'Waiting for Approval', 'Not Submitted', 'Rejected', 'Approved')");
        $users = $query->paginate(5);
        return view('index', compact('users'));
    }

    public function filterUsers(Request $request)
    {
        $query = UserProof::query();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('email') && $request->email != '') {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        $users = $query->get(); 

        return response()->json(['users' => $users]);
    }
    
    public function approve($id, $type)
    {
        $user = UserProof::find($id);
        if (!$user) return response()->json(['error' => 'User not found'], 404);

        if ($type === 'id') {
            $user->id_proof_status = 'Approved';
        } elseif ($type === 'address') {
            $user->address_proof_status = 'Approved';
        }

        // যদি দুটো প্রমাণই Approved হয়, তাহলে পুরো User Approved হবে
        if ($user->id_proof_status === 'Approved' && $user->address_proof_status === 'Approved') {
            $user->status = 'Approved';
        } else {
            $user->status = 'Waiting for Approval';
        }

        $user->save();

        return response()->json(['success' => 'Proof approved successfully']);
    }


    public function reject($id, $type)
    {
        $user = UserProof::find($id);
        if (!$user) return response()->json(['error' => 'User not found'], 404);

        $message = '';

        if ($type === 'id') {
            $user->id_proof_status = 'Rejected';
            $message = 'ID Proof rejected successfully';
        } elseif ($type === 'address') {
            $user->address_proof_status = 'Rejected';
            $message = 'Address Proof rejected successfully';
        }
        if ($user->id_proof_status === 'Rejected' && $user->address_proof_status === 'Rejected') {
            $user->status = 'Rejected';
        } else {
            $user->status = 'Waiting for Approval';
        }

        $user->save();

        return response()->json(['success' => $message]);
    }

    public function reupload(Request $request, $id)
    {
        $request->validate([
            'proof' => 'required|file|mimes:jpg,png,pdf|max:2048',
            'proof_type' => 'required|in:id,address'
        ]);

        $user = UserProof::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($request->proof_type === 'id' && $user->id_proof_status !== 'Rejected') {
            return response()->json(['error' => 'Only rejected ID proofs can be reuploaded.'], 400);
        }
        if ($request->proof_type === 'address' && $user->address_proof_status !== 'Rejected') {
            return response()->json(['error' => 'Only rejected Address proofs can be reuploaded.'], 400);
        }

        $file = $request->file('proof');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('proofs', $filename, 'public');

        if ($request->proof_type === 'id') {
            $user->id_proof = $filePath;
            $user->id_proof_status = 'Waiting for Approval';
        } else {
            $user->address_proof = $filePath;
            $user->address_proof_status = 'Waiting for Approval';
        }

        $user->status = 'Waiting for Approval';
        $user->save();

        return response()->json(['success' => 'Proof reuploaded successfully']);
    }






    



   



}
