<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function branch(Request $request)
    {
        $branch = Branch::create([
            'branch_name' => $request->get('branch_name'),
        ]);
        return response()->json($branch);
    }

    public function getBranch()
    {
        $branchList = Branch::all();
        return response()->json(compact('branchList'));
    }
    public function destroy($id)
    {

        $branch = Branch::findOrFail($id);
        $branch->delete();
        return response()->json($branch);
    }
}
