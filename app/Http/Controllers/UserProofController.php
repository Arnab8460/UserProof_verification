<?php

namespace App\Http\Controllers;
use App\Models\UserProof;
use App\Models\User;

use Illuminate\Http\Request;

class UserProofController extends Controller
{
    public function index(Request $request)
    {
        $query = UserProof::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        $users = $query->orderByRaw("
            CASE 
                WHEN status = 'Waiting for Approval' THEN 1
                WHEN status = 'Not Submitted' THEN 2
                WHEN status = 'Rejected' THEN 3
                ELSE 4
            END
        ")->paginate(10);

        return view('index', compact('users'));
    }
    public function approve($id)
    {
        $user = UserProof::find($id);
        if (!$user) return response()->json(['error' => 'User not found'], 404);

        $user->status = 'Approved';
        $user->save();

        return response()->json(['success' => 'User approved successfully']);
    }

    public function reject($id)
    {
        $user = UserProof::find($id);
        if (!$user) return response()->json(['error' => 'User not found'], 404);

        $user->status = 'Rejected';
        $user->save();

        return response()->json(['success' => 'User rejected successfully']);
    }
    public function reupload(Request $request, $id)
    {
        $request->validate([
            'proof' => 'required|file|mimes:jpg,png,pdf|max:2048'
        ]);

        $user = UserProof::find($id);
        if (!$user) return redirect()->back()->with('error', 'User not found');

        $file = $request->file('proof');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);

        if ($request->proof_type === 'id') {
            $user->id_proof = $filename;
        } else {
            $user->address_proof = $filename;
        }

        $user->status = 'Waiting for Approval';
        $user->save();

        return redirect()->back()->with('success', 'Proof reuploaded successfully');
    }



}
