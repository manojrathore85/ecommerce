<?php

namespace App\Http\Controllers;

use Faker\Factory;
use Illuminate\Http\Request;
use App\Models\Sales;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class SalesController extends Controller
{
   function index(Request $request){
        if($request->ajax()){
            $sales = Sales::query()
            //Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('M d Y');
            ->whereBetween('date',
                [
                    Carbon::createFromFormat('d-m-Y', $request->fromdate)->format('Y-m-d'),
                    Carbon::createFromFormat('d-m-Y', $request->todate)->format('Y-m-d'),
                ])
            ->orderby('saleby', 'asc')
            ->orderby('product_name', 'asc')  
            ->orderby('model_name', 'asc')  
            ->get();
            return DataTables::of($sales)->make(true);
            // return response()->json([
            //     'data'=> $sales,
            // ]);
        }
       return view('saleslist');          
   }
   function saleslist(){

   }
}
