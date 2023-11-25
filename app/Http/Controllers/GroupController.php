<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestGroup;
use App\Models\group;
use Illuminate\Http\Request;
use DataTables;
class GroupController extends Controller
{
    
    public   $nature = ['Income'=>'Income', 'Expences'=>'Expences', 'Assets'=>'Assets', 'Liability'=>'Liability'];
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        if($request->ajax()){
            $data = Group::query();
            $dtbl =  DataTables::of($data)
                ->make(true);
            return $dtbl;
        }
        return view('group.list');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $group= false;
        $groups = Group::pluck('name','id');
        $nature = $this->nature;
        return view('group.groupform', compact('group','groups', 'nature'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestGroup $request, $id = false)
    {
        $validated = $request->validated();
        try {
            $params=[
                'name'=> $validated['name'],
                'parent_id'=>$validated['parentGroup'],
                'nature'=>$validated['nature'],
                'order'=>$validated['order'],
            ];
            Group::create($params);
            return response()->json([
                'status' => 'success',
                'error' => false,
                'message' => 'Record created successfuly',
            ], 200);    
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'error'=> true,
                'message' => 'Getting Error'. $e,
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
        $group = Group::findOrfail($id);
        $groups = Group::pluck('name', 'id');
        $nature = $this->nature;
        
        return view('group.groupform', compact('group','groups', 'nature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestGroup $request, $id)
    {
        $validated = $request->validated();
        try {
            $params=[
                'name'=> $validated['name'],
                'parent_id'=>$validated['parentGroup'],
                'nature'=>$validated['nature'],
                'order'=>$validated['order'],
            ];
            $group = Group::findOrfail($id);
            $group->update($params);
            return response()->json([
                'status'=> 'success',
                'error' => false,
                'message' => 'Record updated successfuly'
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'status'=> 'success',
                'error' => false,
                'message' => 'Getting error'.$e
            ],500);
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
        $group = Group::findOrfail($id);
        $group->delete();
        return response()->json([
            'status' => 'success',
            'error' => false,
            'message' => 'Record deleted successfuly',
        ]);
       } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'error' => true,
                'message' => 'Getting Error'.$e,
            ]);
       }
    }
}
