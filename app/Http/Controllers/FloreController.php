<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestFlore;
use App\Models\Flore;
use Illuminate\Http\Request;

class FloreController extends Controller
{
    public function index(Request $request)
    {
        // echo "asdasd";
        $flore = false;
        $flores = Flore::all();
        return view('flore.form', compact('flores', 'flore'));
    }
    public function store(RequestFlore $request)
    {
        $validated = $request->validated();
        $params = $request->except('_token');
        try {
            Flore::create($params);
            $message = 'Record created successfuly';
            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('fail', 'Getting Error:' . $e);
        }
    }
    public function update(RequestFlore $request, $id)
    {
        $validated = $request->validated();
        $params = $request->except('_token');
        try {
            $flore = Flore::findOrfail($id);
            $flore->update($params);
            $message = 'Record updated successfuly';
            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('fail', 'Getting Error:' . $e);
        }
    }
    public function edit($id)
    {
        $flore = Flore::findOrfail($id);
        $flores = Flore::all();
        return view('flore.form', compact('flore', 'flores'));
    }
    public function destroy($id){
        try {
            Flore::findOrfail($id)->delete();
            return back()->with('success', 'Record deleted successfully');
        } catch (\Exception $e) {
            return back()->with('fail', 'Getting Error:'.$e);
        }
    }
}
