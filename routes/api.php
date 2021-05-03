<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\BranchManagerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'authenticate']);
Route::get('/userlist', [UserController::class, 'getUser']);
Route::get('/edit/{id}', [UserController::class, 'edit']);
Route::post('/update/{id}', [UserController::class, 'update']);
Route::delete('/delete/{id}', [UserController::class, 'destroy']);

//Branch
Route::post('/branch', [BranchController::class, 'branch']);
Route::get('/branchlist', [BranchController::class, 'getBranch']);
Route::delete('/branch/delete/{id}', [BranchController::class, 'destroy']);

//Branch Manager
Route::post('/branch/manager', [BranchManagerController::class, 'branchManager']);
Route::get('/branch/manager/list', [BranchManagerController::class, 'getBranchManager']);
Route::delete('/branch/manager/delete/{id}', [BranchManagerController::class, 'destroy']);

//Employee
Route::post('/employee', [EmployeeController::class, 'employee']);
Route::get('/employee/list', [EmployeeController::class, 'getEmployee']);
Route::get('/branch/employee/list/{id}', [EmployeeController::class, 'getEmpBranchWise']);
Route::delete('/employee/delete/{id}', [EmployeeController::class, 'destroy']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::any('/user', [UserController::class, 'getAuthenticatedUser']);
    Route::get('/closed',  [UserController::class, 'closed']);
});

//Cache clear
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; //Return anything
});
