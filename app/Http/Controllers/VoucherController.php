<?php

namespace App\Http\Controllers;

use App\Events\VoucherCreated;
use App\Http\Requests\RequestVoucher;
use App\Models\Account;
use App\Models\flate;
use App\Models\voucher;
use App\Models\VoucherDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        
        if ($request->ajax()) {
            $voucher = Voucher::query()
                ->get();
            return $datatable = DataTables::of($voucher)->make(true);
        }
        return view('voucher.list');
    }
    public function create(){
        $voucher= false;
        $accounts = Account::query()
            ->select('accounts.*', 'groups.name as groupname', 'cities.name as cityname', 'groups.nature')
            ->join('groups', 'accounts.group_id', '=', 'groups.id')
            ->leftjoin('cities', 'accounts.city_id', '=', 'cities.id')
            ->get();
        $accounts = $accounts->pluck('name', 'id');
        $flates = Flate::all();
        //->put('','Please Select');
        //dd($incomeheads);
        return view('voucher.form', compact('voucher','flates', 'accounts'));
    }
    public function store(RequestVoucher $request)
    {

        $validated = $request->validated();
        $flates = Flate::all();
        $incomeAccount = Account::findOrfail($request->input('cr_account_id'));
        
        DB::beginTransaction();
        //try {
            $insertedVoucher = Voucher::create([
                'date' => $request->post('date'),
                'no' => Voucher::max('no') + 1,
                'type' => $request->post('type'),
                'account_id' => $request->post('cr_account_id'),
                'naration' => $request->post('naration'),
                'amount' => $request->post('amount'),
                'drcr' => 'CR',
            ]);
            $totalCr = 0;
            foreach ($request->post('flate_id') as $flateid) {
                $voucherDetails[] = [
                    'voucher_id' => $insertedVoucher->id,
                    'account_id' => $request->post('dr_account_id'),
                    'flate_id' => $flateid,
                    'amount' => $itemAmount = ($incomeAccount->category == 'Maintenance' ? $this->getMaintenanceAmount($flateid, $flates): $request->post('amount')),
                    'naration' => $request->post('naration'),
                    'drcr' => 'DR',
                ];
                $totalCr = $totalCr + $itemAmount;
            }
            // echo "<pre>";
            // print_r($voucherDetails);
            // echo "</pre>";
            VoucherDetail::insert($voucherDetails);
            Voucher::findOrfail($insertedVoucher->id)->update(['amount' => $totalCr]);
            DB::commit();
            $emails= ['manojrathore85@gmail.com', 'manojrathore85rnd@gmail.com'];
            event(new VoucherCreated($emails));
            return back()->with('success', 'Record save successfuly');
        //} catch (\Exception $e) {
          //  DB::rollback();
            //return back()->with('fail', 'Getting Error:'.$e);
        //}
    }
    public function voucherDetail($id){
        try {
            $voucherDetail = VoucherDetail::query()
            ->join ('flates', 'voucher_details.flate_id','=','flates.id')
            ->join ('accounts', 'voucher_details.account_id','=','accounts.id')
            ->where('voucher_id',$id)->get();
            return response()->json([
                'status' => 'success',
                'error' => false,
                'data' => $voucherDetail,                
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'success',
                'error' => false,
                'message' => 'Getting Error:'.$e,                
            ]);
        }
    }
    public function getMaintenanceAmount($flateId,$flates){
        if($flates = $flates->where('id',$flateId)->first()){
            return $flates->maintenance_amount;
        }else{
            return 0;
        }
    }
    public function destroy(Request $request){

        DB::beginTransaction();
        try {
            
            $voucher = Voucher::findOrfail($request->id);
            VoucherDetail::where('voucher_id', '=', $voucher->id)->delete();
            $voucher->delete();
            
            DB::commit();
            return response()->json([
                'status' => 'success',
                'error' => 'true',
                'message'=> 'Record deleted successfuly'
            ]);
            //code...
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'success',
                'error' => 'true',
                'message'=> 'Getting Error:'.$e
            ]);
           // return back()->with('fail','Getting Error:'.$e);
        }

    }
    public function edit($id){
        try {
            $accounts = Account::query()
            ->select('accounts.*', 'groups.name as groupname', 'cities.name as cityname', 'groups.nature')
            ->join('groups', 'accounts.group_id', '=', 'groups.id')
            ->leftjoin('cities', 'accounts.city_id', '=', 'cities.id')
            ->get();
            $accounts = $accounts->pluck('name', 'id');
            $flates = Flate::all();
            $voucher = DB::table('vouchers as v')
                        ->select('v.*','v.id as v_id','v.account_id as cr_account_id', 'vd.account_id as dr_account_id','vd.*', 'vd.id as vd_id' )
                        ->join('voucher_details as vd' ,'v.id', '=','vd.voucher_id') 
                        ->where('v.id','=', $id)
                        ->get();
            //dd($voucher[0]->flate_id);          
            return view('voucher.edit', compact('voucher','flates', 'accounts'));
           

        } catch (\Throwable $th) {
            return back()->with('fail', 'Getting Error'.$th);
        }
    }
    public function update($id, RequestVoucher $request){      
       // try{
            //dd($id);   
            $validated = $request->validated();
            $flates = Flate::all();
            $incomeAccount = Account::findOrfail($request->input('cr_account_id'));
            $existvdid = VoucherDetail::where('voucher_id',$id)->pluck('id')->toArray();
            // print_r($existvdid);
            // echo "<pre>"; print_r($request->input('flate_id')); echo "</pre>";
            // echo "<pre>"; print_r($request->input('vd_id')); echo "</pre>";
            $currentvdid = [];

            //DB::beginTransaction();
            //try {
                $totalCr = 0;
                foreach ($request->input('flate_id') as $key =>$flateid) {
                    $currentvdid[] =  $request->input('vd_id')[$flateid];
                    $newdata [] =  [
                            'id' => $request->input('vd_id')[$flateid],
                            'voucher_id' => $id,
                            'account_id' => $request->input('dr_account_id'),
                            'flate_id' => $flateid,
                            'amount' => $itemAmount = ($incomeAccount->category == 'Maintenance' ? $this->getMaintenanceAmount($flateid, $flates): $request->input('amount')),
                            'naration' => $request->input('naration'),
                            'drcr' => 'DR',

                    ];
                    
                    $totalCr = $totalCr + $itemAmount;
                }
                echo "<pre>"; print_r($newdata); echo "<pre>";
                VoucherDetail::upsert($newdata, ['id']);

                Voucher::findOrfail($id)->update([
                    'date' => $request->input('date'),
                    'account_id' => $request->input('cr_account_id'),
                    'naration' => $request->input('naration'),
                    'amount' => $totalCr,
                    'drcr' => 'CR',
                ]);
                $delete_vd_id = array_diff($existvdid, $currentvdid);
                VoucherDetail::destroy($delete_vd_id);
                DB::commit();
                //$emails= ['manojrathore85@gmail.com', 'manojrathore85rnd@gmail.com'];
                //event(new VoucherCreated($emails));
                return back()->with('success', 'Record save successfuly');




        // }catch(\Exception $e){
        //     echo "Gettign Error ".$e;
        // }
    }
    
}
