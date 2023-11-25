<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestFlate;
use App\Models\flate;
use App\Models\Flore;

class FlateController extends Controller
{
    public $status_option = [
        'NA' => 'Na',
        'Sold'=>'Sold',
        'OcupidByOwner' => 'OcupidByOwner', 
        'OcupidByTenant' => 'OcupidByTenant', 
        'OpenForSale' => 'OpenForSale', 
        'OpenForRent' => 'OpenForRent', 
       ];
    public function index(){
       $flate =false;
       $flores = Flore::pluck('flore_no', 'id'); 
       $flores = $flores->put('','Please Select');
       $flates = Flate::query()
       ->select('flates.*','flores.flore_no')
       ->join('flores','flates.flore_id','=','flores.id')
       ->get();
       $status_option = $this->status_option;
       return view('flate.form', compact('flates', 'flores','flate','status_option')); 
    }
    public function store(RequestFlate $request){
        $validated = $request->validated();
        $params = $request->except('_token');
        try {
            Flate::create($params);
            return back()->with('success', 'Record save successfuly');
        } catch (\Exception $e) {
            return back()->with('fail', 'Getting Error:'.$e);
        }
    }
    public function update(RequestFlate $request, $id){
        $validated = $request->validated();
        $params = $request->except('_token');
        try {
            $flate = Flate::findOrfail($id);
            $flate->update($params);
            return back()->with('success', 'Record updated successfully');
        } catch (\Exception $e) {
            return back()->with('fail', 'Getting Error'.$e);
        }
    }
    public function edit($id, $copy=false){
                
        $flate = Flate::findOrfail($id);
        $flate->copy = $copy;
        $flores = Flore::pluck('flore_no','id');
        $flates = Flate::query()
        ->select('flates.*','flores.flore_no')
        ->join('flores','flates.flore_id','=','flores.id')
        ->get();
        $status_option = $this->status_option;
        return view('flate.form', compact('flate', 'flores', 'flates', 'status_option'));
    }
    public function destroy($id){
        try{
            $flate = Flate::findOrfail($id)->delete();
            return back()->with('success', 'Record deleted successfuly');
        }catch(\Exception $e){
            return back()->with('fail', 'Getting Error'.$e);
        }
    }
    public function copy($id){
        $flate = Flate::findOrfail($id);
        $flores = Flore::pluck('flore_no','id');
        $flates = Flate::query()
        ->select('flates.*','flores.flore_no')
        ->join('flores','flates.flore_id','=','flores.id')
        ->get();
        $status_option = $this->status_option;
        $copy= true;
        return view('flate.form', compact('flate', 'flores', 'flates', 'status_option', 'copy'));
    }
}
