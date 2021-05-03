<?php

namespace App\Http\Controllers;

use App\Models\BranchManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchManagerController extends Controller
{
    public function branchManager(Request $request)
    {

        $branch = BranchManager::firstOrCreate([
            'branch_id' => $request->get('branch_id'),
            'user_id' => $request->get('user_id'),
        ]);
        return response()->json($branch);
    }

    public function getBranchManager()
    {
        $branchManagerList = BranchManager::all();
        return response()->json(compact('branchManagerList'));
    }
    public function destroy($id)
    {
      //  return 'fhdishfdoshfds';
        $branchManager = BranchManager::findOrFail($id);
        $branchManager->delete();
        return response()->json($branchManager);
    }
}
