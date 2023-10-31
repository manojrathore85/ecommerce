<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestAccount;
use App\Models\account;
use App\Models\City;
use App\Models\group;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $account = account::query()
            ->select('accounts.*','groups.name as groupname', 'cities.name as cityname', 'groups.nature')
            ->join('groups', 'accounts.group_id', '=', 'groups.id')
            ->leftjoin('cities', 'accounts.city_id', '=', 'cities.id')
            ->get();
            $datatable = DataTables::of($account)->make(true);
            //dd($datatable);
            return $datatable;
        }
        return view('account.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $account = false;
        $groups = group::pluck('name','id');
        $cities = City::pluck('name','id');
        return view ('account.form', compact('account','groups','cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestAccount $request)
    {
        $validated = $request->validated();
        $params = $request->except(['_token']);
        $params['created_by'] =  Auth::id();
        try {
            account::create($params);
            return response()->json([
                'status'=> 'success',
                'error' => false,
                'message' => 'Record Save Successfuly',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'error' => true,
                'message' => 'Getting Error:'.$e,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $account = account::findOrfail($id);
        $groups = group::pluck('name', 'id');
        $cities = City::pluck('name', 'id');
        return view('account.form',compact('account','groups', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestAccount $request, $id)
    {
        $validated = $request->validated();
        try {
            $params = $request->except('_token');
            $account = account::findOrfail($id);
            $account->update($params);
            return response()->json([
                'status' => 'success',
                'error' => false,
                'message' => 'Record successfuly updated',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'error' => true,
                'message' => 'GettingError:'.$e,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $account = account::findOrfail($id);
            $account->delete();
            return response()->json([
                'status' => 'success',
                'error' => false,
                'message' => 'Record deleted successfuly',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'error' => false,
                'message' => 'GettingError'.$e,
            ]);
        }
    }
}
