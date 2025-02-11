<?php

namespace App\Http\Controllers;
use App\Models\UserProof;
use App\Models\Proof;
use App\Models\User;

use Illuminate\Http\Request;

class UserProofController extends Controller
{
    // public function index(Request $request)
    // {
    //     $query = UserProof::query();

    //     if ($request->has('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     if ($request->has('email')) {
    //         $query->where('email', 'like', '%' . $request->email . '%');
    //     }

    //     $users = $query->orderByRaw("
    //         CASE 
    //             WHEN status = 'Waiting for Approval' THEN 1
    //             WHEN status = 'Not Submitted' THEN 2
    //             WHEN status = 'Rejected' THEN 3
    //             ELSE 4
    //         END
    //     ")->paginate(10);

    //     return view('index', compact('users'));
    // }
    // public function approve($id)
    // {
    //     $user = UserProof::find($id);
    //     if (!$user) return response()->json(['error' => 'User not found'], 404);

    //     $user->status = 'Approved';
    //     $user->save();

    //     return response()->json(['success' => 'User approved successfully']);
    // }

    // public function reject($id)
    // {
    //     $user = UserProof::find($id);
    //     if (!$user) return response()->json(['error' => 'User not found'], 404);

    //     $user->status = 'Rejected';
    //     $user->save();

    //     return response()->json(['success' => 'User rejected successfully']);
    // }
    // public function reupload(Request $request, $id)
    // {
    //     $request->validate([
    //         'proof' => 'required|file|mimes:jpg,png,pdf|max:2048'
    //     ]);

    //     $user = UserProof::find($id);
    //     if (!$user) return redirect()->back()->with('error', 'User not found');

    //     $file = $request->file('proof');
    //     $filename = time() . '_' . $file->getClientOriginalName();
    //     $file->move(public_path('uploads'), $filename);

    //     if ($request->proof_type === 'id') {
    //         $user->id_proof = $filename;
    //     } else {
    //         $user->address_proof = $filename;
    //     }

    //     $user->status = 'Waiting for Approval';
    //     $user->save();

    //     return redirect()->back()->with('success', 'Proof reuploaded successfully');
    // }

    public function index(Request $request)
    {
        $query = UserProof::query();
    
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
    
        if ($request->has('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
    
        $users = $query->paginate(10);
    
        return view('index', compact('users'));
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

    // If both proofs are approved, update overall status
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

    if ($type === 'id') {
        $user->id_proof_status = 'Rejected';
    } elseif ($type === 'address') {
        $user->address_proof_status = 'Rejected';
    }

    $user->status = 'Rejected';
    $user->save();

    return response()->json(['success' => 'Proof rejected successfully']);
}

public function reupload(Request $request, $id)
{
    $request->validate([
        'proof' => 'required|file|mimes:jpg,png,pdf|max:2048',
        'proof_type' => 'required|in:id,address'
    ]);

    $user = UserProof::find($id);
    if (!$user) return redirect()->back()->with('error', 'User not found');

    if ($request->proof_type === 'id' && $user->id_proof_status !== 'Rejected') {
        return redirect()->back()->with('error', 'Only rejected proofs can be reuploaded.');
    }
    if ($request->proof_type === 'address' && $user->address_proof_status !== 'Rejected') {
        return redirect()->back()->with('error', 'Only rejected proofs can be reuploaded.');
    }

    $file = $request->file('proof');
    $filename = time() . '_' . $file->getClientOriginalName();
    $file->move(public_path('uploads'), $filename);

    if ($request->proof_type === 'id') {
        $user->id_proof = $filename;
        $user->id_proof_status = 'Waiting for Approval';
    } else {
        $user->address_proof = $filename;
        $user->address_proof_status = 'Waiting for Approval';
    }

    $user->status = 'Waiting for Approval';
    $user->save();

    return redirect()->back()->with('success', 'Proof reuploaded successfully');
}



   



}
