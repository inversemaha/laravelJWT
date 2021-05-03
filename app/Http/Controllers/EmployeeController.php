<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function employee(Request $request)
    {

        $employee = Employee::firstOrCreate([
            'branch_id' => $request->get('branch_id'),
            'user_id' => $request->get('user_id'),
        ]);
        return response()->json($employee);
    }

    public function getEmployee()
    {
        $employeeList = Employee::all();
        return response()->json(compact('employeeList'));
    }

    public function getEmpBranchWise($id)
    {
        $employeeList = Employee::where('branch_id', $id)->get();
        return response()->json(compact('employeeList'));
    }
    public function destroy($id)
    {
        //  return 'fhdishfdoshfds';
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return response()->json($employee);
    }
}
