@extends('layout.authapp')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Journal Voucher Report</h4>
            <?php $frommdate = isset($formdata['fromdate']) ? $formdata['fromdate'] :old('fromdate'); ?>
            <?php $todate = isset($formdata['todate']) ? $formdata['todate'] :old('todate'); ?>
            <?php $account_id = isset($formdata['account_id']) ? $formdata['account_id'] :old('account_id'); ?>
            <?php $flate_id = isset($formdata['flate_id']) ? $formdata['flate_id'] :old('flate_id'); ?>

            <form action="{{url('\report\journal')}}" method="GET">
                <x-input type="date" name="fromdate" id="fromdate" label="From Date" :value="$frommdate" />
                <x-input type="date" name="todate" id="todate" label="To Date" :value="$todate" />
                <x-select name="account_id" id="account_id" label="Account Name" :options="$accounts" :selected="$account_id" />
                <x-select name="flate_id" id="flate_id" label="Flate No" :options="$flates" :selected="$flate_id" />
                <button class="btn btn-sm btn-primary">Search</button>
            </form>
        </div>
        <div class="card-body">
        <table id="table-journal" class="table table-bordered table-striped">
            <thead>
                <tr>
                <th>sno.</th>
                <th>Date</th>
                <th>No</th>
                <th>Type</th>
                <th>Account Name</th>
                <th>Flate</th>
                <th>Dr Amount</th>
                <th>Cr Amount</th>
                <th>DR/CR</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $rowNumber = 0; 
                @endphp
                <?php  
                //print_r($row); 
                //exit;
                ?>
                @foreach($journalData as $key => $row)
         
                <tr>
                    <td>{{$key > 0 && $row->no === $journalData[$key - 1]->no ? ' ' : $rowNumber+1}}</td>
                    <td>{{$key > 0 && $row->no === $journalData[$key - 1]->no ? '-' :  \Carbon\Carbon::parse($row->date)->format('d-m-Y')}}</td>
                    <td>{{$key > 0 && $row->no === $journalData[$key - 1]->no ? '-' : $row->no}}</td>
                    <td>{{$key > 0 && $row->no === $journalData[$key - 1]->no ? '-' : $row->type}}</td>
                    <td>{{$row->name}}</td>
                    <td>{{$row->flate}}</td>
                    <td>{{$row->DR}}</td>
                    <td>{{$row->CR}}</td>
                    <td>{{$row->drcr}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        <div class="card-footer">

        </div>
    </div>
</div>
@endsection