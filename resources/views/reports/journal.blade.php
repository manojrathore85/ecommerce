@extends('layout.authapp')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Journal Voucher Report</h4>

        </div>
        <div class="card-body">
            <div class="row">
                <?php $frommdate = isset($formdata['fromdate']) ? $formdata['fromdate'] :old('fromdate'); ?>
                <?php $todate = isset($formdata['todate']) ? $formdata['todate'] :old('todate'); ?>
                <?php $account_id = isset($formdata['account_id']) ? $formdata['account_id'] : 
                (old('account_id') != null ? old('account_id') : 'All'); ?>
                <?php $flate_id = isset($formdata['flate_id']) ? $formdata['flate_id'] :(old('flate_id') != null ? old('flate_id') : 'All')  ; ?>
    
                <form action="{{url('report\journal')}}" method="POST">
                    @csrf
                    <x-input type="date" name="fromdate" id="fromdate" label="From Date" :value="$frommdate"  errorname="fromdate" />
                    <x-input type="date" name="todate" id="todate" label="To Date" :value="$todate" errorname="todate"/>
                    <x-select name="account_id" id="account_id" label="Account Name" :options="$accounts" :selected="$account_id"  errorname="account_id"/>
                    <x-select name="flate_id" id="flate_id" label="Flate No" :options="$flates" :selected="$flate_id"  errorname="flate_id"/>
                    <button class="btn btn-primary mb-2 float-end">Search</button>
                </form>
                <hr>
            </div>
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
                @if ($journalData)
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
                @endif
            </tbody>
        </table>
        </div>
        <div class="card-footer">

        </div>
    </div>
</div>
@endsection