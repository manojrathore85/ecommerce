<?php

namespace App\Http\Controllers;

use App\Http\Requests\register;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Http\Client\Events\ResponseReceived;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $roles =  ['1' => 'admin', '2' => 'manager', '3' => 'user'];
    public function index()
    {
        return view('auth.users');
    }
    public function getUsers(Request $request)
    {
        $data = User::query();
        $dtbl =  Datatables::of($data)
            ->make(true);
        return $dtbl;
    }
    public function manage($id = '')
    {
        $user = $id ? User::find($id) : false;
        $roles = $this->roles;
        return view('auth.user', compact('roles', 'user'));
    }
    public function store(register $request, $id = '')
    {
        $validated = $request->validated();
        try {
            $params = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'gender' => $validated['gender'],
                'role' => $validated['role'],
            ];
            if ($id) {
                $user = User::findOrfail($id);
                $user->update($params);
                $message = 'User updated successfuly';
            } else {
                $params['password'] = Hash::make($validated['password']);
                User::create($params);
                $message = 'User created successfuly';
            }
            return $this->responceReturn($request, 'success',  $message, false, 200);
        } catch (\Exception $e) {
            return $this->responceReturn($request, 'fail', 'Error:' . $e->getMessage(), [['Error:' . $e->getMessage()]], 500);
        }
    }
    public function delete(Request $request)
    {   
        try {
            User::findOrfail($request->id)->delete();
            return $this->responceReturn($request, 'success', 'Record deleted successfuly', false, 200);
        } catch (\Exception $e) {
            return $this->responceReturn($request, 'fail', 'Error:' . $e->getMessage(), [['Error:' . $e->getMessage()]], 500);
        }
    }
    /**
     * function is use to return respnoce according the request if request from the from / web it return back url and 
     * if request from the ajax or api its returning the json responce
     */
    public function responceReturn(Request $request, $status, $message, $errors, $statuscode)
    {
        if ($request->is('api/*') || $request->ajax()) {
            return response()->json([
                'status' => $status,
                'errors' => $errors,
                'message' => $message,
            ], $statuscode);
        } else {
            return back()->with($status, $message);
        }
    }
}
