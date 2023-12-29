<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Flate;
use App\Models\VoucherDetail;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportController extends Controller
{
    public function flateLedgerView(){
        $data= false;
        $accounts= Account::pluck('name','id');
        $accounts->put(0, 'PleaseSelect');
        $flates= Flate::pluck('flate_no','id');
        $flates->put('All', 'All');
        return view('reports.flateledger', compact('data','accounts','flates'));     
    }
    public function accountLedgerView(){
        $data = false;
        $accounts = Account::pluck('name','id');
        $accounts->put(0,'PleaseSelect');
        return view('reports.accountLedger', compact('data','accounts'));
    }
    public function journal(Request $request){
        $accounts= Account::pluck('name','id');
        $accounts->put('All', 'All');
        $flates= Flate::pluck('flate_no','id');
        $flates->put('All', 'All');
        $formdata = $request->only(['fromdate','todate','account_id','flate_id']);

        //get the data from voucher table
        $Voucher = DB::table('vouchers as v')
        ->select(DB::raw('0 as vid'), 'v.date', 'v.no', 'v.type', 'v.account_id', 'a.name', DB::raw("'NA' as flate"),
            DB::raw('SUM(CASE WHEN v.drcr = "DR" THEN round(abs(v.amount),2) ELSE 0 END) as DR'),
            DB::raw('SUM(CASE WHEN v.drcr = "CR" THEN round(abs(v.amount),2) ELSE 0 END) as CR'), 'v.drcr')
        ->join('accounts as a', 'v.account_id', '=', 'a.id')
        ->when($request->input('account_id') != 'All', function ($query) use ($request) {
            $query->where('v.account_id', '=', $request->input('account_id'));
        })
        ->whereBetween('date',[$request->input('fromdate'), $request->input('todate')])
        ->groupBy('v.no', 'v.account_id', 'v.drcr');

        //get the data from the voucher detail table
        $voucherDetail=DB::table('voucher_details as vd')
        ->select('v.id as vid', 'v.date', 'v.no', 'v.type', 'vd.account_id', 'a.name', 'f.flate_no as flate',
            DB::raw('SUM(CASE WHEN vd.drcr = "DR" THEN round(abs(vd.amount),2) ELSE 0 END) as DR'),
            DB::raw('SUM(CASE WHEN vd.drcr = "CR" THEN round(abs(vd.amount),2) ELSE 0 END) as CR'), 'vd.drcr')
        ->join('vouchers as v', 'vd.voucher_id', '=', 'v.id')
        ->join('accounts as a', 'vd.account_id', '=', 'a.id')
        ->leftjoin('flates as f', 'vd.flate_id', '=', 'f.id')
        ->when($request->input('account_id') != 'All', function ($query) use ($request) {
            $query->where('vd.account_id', '=', $request->input('account_id'));
        })
        ->when($request->input('flate_id') != 'All', function ($query) use ($request) {
            $query->where('vd.flate_id', '=', $request->input('flate_id'));
        })
        ->whereBetween('v.date',[$request->input('fromdate'), $request->input('todate')])
        ->groupBy('v.no', 'vd.account_id', 'f.flate_no', 'vd.drcr');

        //join the both table data together
      
         if($request->input('flate_id') == 'All'){
            $journalData = $Voucher->union($voucherDetail);
         }else{
            $journalData= $voucherDetail;
         }
        $journalData = $journalData->orderBy('no')
        ->get();
        //echo    $journalData->toSql();
        return view('reports.journal', compact('journalData','accounts','flates','formdata'));        
    }
    public function flateLedger(Request $request){
        $validated= $request->validate([
            'fromdate'=> 'required',
            'todate'=> 'required',
            'account_id' => 'required|gt:0',
            //'flate_id' => 'required',
        ],[
            'fromdate'=> 'From date can not be empty',
            'todate'=> 'To date can not be enpty',
            'account_id' => 'Please select a account',
            //'flate_id' => 'required',
        ]);
        // echo "Hellow";
        // exit;
        $accounts= Account::pluck('name','id');
        $accounts->put(0, 'PleaseSelect');
        $flates= Flate::pluck('flate_no','id');
        $flates->put('All', 'All');
        $formdata = $request->only(['fromdate','todate','account_id','flate_id']);
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
        //get opening balance from the master
        $masterOpening=DB::table('flates as f')
        ->select(
            DB::raw('0 as vid'),
            DB::raw('0 as date'),
            DB::raw('0 as no'),
            DB::raw('0 as type'),
            DB::raw('0 as account_id'),
            DB::raw('0 as name'),
            DB::raw('0 as flate'),
            DB::raw('0 as flate_id'),
            DB::raw('SUM(CASE WHEN f.drcr = "DR" THEN f.op_balance ELSE 0 END) AS DR'),
            DB::raw('SUM(CASE WHEN f.drcr = "CR" THEN f.op_balance ELSE 0 END) AS CR'),
            DB::raw('0 as drcr')
        )
        ->where('f.id','=',$request->input('flate_id'));

        //get opeining balance from the voucher before the date transaction 
        $opening = DB::table('voucher_details as vd')
        ->select(
            DB::raw('0 as vid'),
            DB::raw('0 as date'),
            DB::raw('0 as no'),
            DB::raw('0 as type'),
            DB::raw('0 as account_id'),
            DB::raw('0 as name'),
            DB::raw('0 as flate'),
            DB::raw('0 as flate_id'),
            DB::raw('SUM(CASE WHEN vd.drcr = "DR" THEN vd.amount ELSE 0 END) AS DR'),
            DB::raw('SUM(CASE WHEN vd.drcr = "CR" THEN vd.amount ELSE 0 END) AS CR'),
            DB::raw('0 as drcr')
        )
        ->join('flates as f','vd.flate_id', 'f.id')
        ->join('vouchers as v','vd.voucher_id', 'v.id')
        ->where('vd.flate_id', '=',$request->input('flate_id'))
        ->where('vd.account_id', '=', $request->input('account_id'))
        ->where('v.date','<',$request->input('fromdate'));

        $opb = DB::table($masterOpening->union($opening))
        ->select(
            DB::raw('0 as vid'),
            DB::raw('0 as date'),
            DB::raw('0 as no'),
            DB::raw('0 as type'),
            DB::raw('0 as account_id'),
            DB::raw('"Opening Balance" as name'),
            DB::raw('0 as flate'),
            DB::raw('0 as flate_id'),
            DB::raw('CASE WHEN SUM(DR) > SUM(CR) THEN SUM(DR) - SUM(CR) ELSE 0 END AS DR'),
            DB::raw('CASE WHEN SUM(CR) > SUM(DR) THEN SUM(CR) - SUM(DR) ELSE 0 END AS CR'),
            DB::raw('0 as drcr')
        );

        //get the data from voucher table
        $Voucher = DB::table('vouchers as v')
        ->select(DB::raw('0 as vid'), 'v.date', 'v.no', 'v.type', 'v.account_id', 'a.name', 
            DB::raw("'NA' as flate"),
            DB::raw("'NA' as flate_id"),
            DB::raw('SUM(CASE WHEN v.drcr = "DR" THEN v.amount ELSE 0 END) as DR'),
            DB::raw('SUM(CASE WHEN v.drcr = "CR" THEN v.amount ELSE 0 END) as CR'), 'v.drcr')
        ->join('accounts as a', 'v.account_id', '=', 'a.id')
        ->when($request->input('account_id') != 0, function ($query) use ($request) {
            $query->where('v.account_id', '=', $request->input('account_id'));
        })
        ->whereBetween('date',[$request->input('fromdate'), $request->input('todate')])
        ->groupBy('v.no', 'v.account_id', 'v.drcr');

        //get the data from the voucher detail table
        $voucherDetail=DB::table('voucher_details as vd')
        ->select('v.id as vid', 'v.date', 'v.no', 'v.type', 'vd.account_id', 'a.name', 'f.flate_no as flate', 
            DB::raw('vd.flate_id as flate_id'),
            DB::raw('SUM(CASE WHEN vd.drcr = "DR" THEN vd.amount ELSE 0 END) as DR'),
            DB::raw('SUM(CASE WHEN vd.drcr = "CR" THEN vd.amount ELSE 0 END) as CR'), 'vd.drcr')
        ->join('vouchers as v', 'vd.voucher_id', '=', 'v.id')
        ->join('accounts as a', 'vd.account_id', '=', 'a.id')
        ->leftjoin('flates as f', 'vd.flate_id', '=', 'f.id')
        ->when($request->input('account_id') != 0 , function ($query) use ($request) {
            $query->where('vd.account_id', '=', $request->input('account_id'));
        })
        ->when($request->input('flate_id') != 'All', function ($query) use ($request) {
            $query->where('vd.flate_id', '=', $request->input('flate_id'));
        })
        ->whereBetween('v.date',[$request->input('fromdate'), $request->input('todate')])
        ->groupBy('v.no', 'vd.account_id', 'f.flate_no', 'vd.drcr');

        //join the both table data together      
         if($request->input('flate_id') == 'All'){
            $data = $Voucher->union($voucherDetail);
         }else{
            $data = $opb->union($voucherDetail);           
         }
        $data = $data->orderBy('no')
        ->get();
        //$data = $this->ledgerFormate($data);
        //echo    $journalData->toSql();
        return view('reports.flateLedger', compact('data','accounts','flates','formdata'));        
    }
    public function accountLedger(Request $request){
        $validated= $request->validate([
            'fromdate'=> 'required',
            'todate'=> 'required',
            'account_id' => 'required|gt:0',
            //'flate_id' => 'required',
        ],[
            'fromdate'=> 'From date can not be empty',
            'todate'=> 'To date can not be enpty',
            'account_id' => 'Please select a account',
            //'flate_id' => 'required',
        ]);
        // echo "Hellow";
        // exit;
        $accounts= Account::pluck('name','id');
        $accounts->put(0, 'PleaseSelect');
        // $flates= Flate::pluck('flate_no','id');
        // $flates->put('All', 'All');
        $formdata = $request->only(['fromdate','todate','account_id','flate_id']);
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
        //get opening balance from the master
        $masterOpening=DB::table('accounts as a')
        ->select(
            DB::raw('0 as vid'),
            DB::raw('0 as date'),
            DB::raw('0 as voucher_id'),
            DB::raw('0 as no'),
            DB::raw('0 as type'),
            DB::raw('0 as account_id'),
            DB::raw('0 as name'),

            DB::raw('SUM(CASE WHEN a.drcr = "DR" THEN a.op_balance ELSE 0 END) AS DR'),
            DB::raw('SUM(CASE WHEN a.drcr = "CR" THEN a.op_balance ELSE 0 END) AS CR'),
            DB::raw('0 as drcr')
        )
        ->where('a.id','=',$request->input('account_id'));

        //get opeining balance from the voucher before the date transaction 
        $openingVoucher = DB::table('vouchers as v')
        ->select(
            DB::raw('0 as vid'),
            DB::raw('0 as date'),
            DB::raw('0 as voucher_id'),
            DB::raw('0 as no'),
            DB::raw('0 as type'),
            DB::raw('0 as account_id'),
            DB::raw('0 as name'),

            DB::raw('SUM(CASE WHEN v.drcr = "DR" THEN v.amount ELSE 0 END) AS DR'),
            DB::raw('SUM(CASE WHEN v.drcr = "CR" THEN v.amount ELSE 0 END) AS CR'),
            DB::raw('0 as drcr')
        )
        //->join('flates as f','vd.flate_id', 'f.id')
        //->join('vouchers as v','vd.voucher_id', 'v.id')     
        ->where('v.account_id', '=', $request->input('account_id'))
        ->where('v.date','<',$request->input('fromdate'));
         //get opeining balance from the voucher detail before the date transaction     
        $openingVoucherDetail = DB::table('voucher_details as vd')
        ->select(
            DB::raw('0 as vid'),
            DB::raw('0 as date'),
            DB::raw('0 as voucher_id'),
            DB::raw('0 as no'),
            DB::raw('0 as type'),
            DB::raw('0 as account_id'),
            DB::raw('0 as name'),

            DB::raw('SUM(CASE WHEN vd.drcr = "DR" THEN vd.amount ELSE 0 END) AS DR'),
            DB::raw('SUM(CASE WHEN vd.drcr = "CR" THEN vd.amount ELSE 0 END) AS CR'),
            DB::raw('0 as drcr')
        )
        ->join('flates as f','vd.flate_id', 'f.id')
        ->join('vouchers as v','vd.voucher_id', 'v.id')     
        ->where('vd.account_id', '=', $request->input('account_id'))
        ->where('v.date','<',$request->input('fromdate'));

        $opb = DB::table(
            $masterOpening->union($openingVoucher)
            ->union($openingVoucherDetail)
            )
        ->select(
            DB::raw('0 as vid'),
            DB::raw('0 as date'),
            DB::raw('0 as voucher_id'),
            DB::raw('0 as no'),
            DB::raw('0 as type'),
            DB::raw('0 as account_id'),
            DB::raw('"Opening Balance" as name'),

            DB::raw('CASE WHEN SUM(DR) > SUM(CR) THEN SUM(DR) - SUM(CR) ELSE 0 END AS DR'),
            DB::raw('CASE WHEN SUM(CR) > SUM(DR) THEN SUM(CR) - SUM(DR) ELSE 0 END AS CR'),
            DB::raw('0 as drcr')
        );

        //get the data from voucher table
        $Voucher = DB::table('vouchers as v')
        ->select(DB::raw('0 as vid'), 'v.date','v.id as voucher_id', 'v.no', 'v.type', 'v.account_id', 'a.name', 

            DB::raw('SUM(CASE WHEN v.drcr = "DR" THEN v.amount ELSE 0 END) as DR'),
            DB::raw('SUM(CASE WHEN v.drcr = "CR" THEN v.amount ELSE 0 END) as CR'), 'v.drcr')
        ->join('accounts as a', 'v.account_id', '=', 'a.id')
        ->when($request->input('account_id') != 0, function ($query) use ($request) {
            $query->where('v.account_id', '=', $request->input('account_id'));
        })
        ->whereBetween('date',[$request->input('fromdate'), $request->input('todate')])
        ->groupBy('v.no', 'v.account_id', 'v.drcr');

        //get the data from the voucher detail table
        $voucherDetail=DB::table('voucher_details as vd')
        ->select('v.id as vid', 'v.date', 'v.id as voucher_id','v.no', 'v.type', 'vd.account_id', 'v.naration', 
            DB::raw('SUM(CASE WHEN vd.drcr = "DR" THEN vd.amount ELSE 0 END) as DR'),
            DB::raw('SUM(CASE WHEN vd.drcr = "CR" THEN vd.amount ELSE 0 END) as CR'), 'vd.drcr')
        ->join('vouchers as v', 'vd.voucher_id', '=', 'v.id')
        ->join('accounts as a', 'vd.account_id', '=', 'a.id')
        ->leftjoin('flates as f', 'vd.flate_id', '=', 'f.id')
        ->when($request->input('account_id') != 0 , function ($query) use ($request) {
            $query->where('vd.account_id', '=', $request->input('account_id'));
        })

        ->whereBetween('v.date',[$request->input('fromdate'), $request->input('todate')])
        ->groupBy('v.no', 'vd.account_id', 'vd.drcr');

        //join the both table data together      
        $Voucher->union($voucherDetail);
        $data = $opb->union($Voucher);           
         
         $data = $data->orderBy('no')->get();

             // Paginate the results
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $paginator = new LengthAwarePaginator(
            $data->forPage($currentPage, $perPage),
            count($data),
            $perPage,
            $currentPage
        );
        $data = $paginator;
        //->get();
        //$data = $this->ledgerFormate($data);
        //echo    $journalData->toSql();
        return view('reports.accountLedger', compact('data','accounts','formdata'));        
    }
}
