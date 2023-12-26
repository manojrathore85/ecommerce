@extends('layout.authapp')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Ledger Report</h4>
            <?php $frommdate = isset($formdata['fromdate']) ? $formdata['fromdate'] :old('fromdate'); ?>
            <?php $todate = isset($formdata['todate']) ? $formdata['todate'] :old('todate'); ?>
            <?php $account_id = isset($formdata['account_id']) ? $formdata['account_id'] :old('account_id'); ?>
            <?php $flate_id = isset($formdata['flate_id']) ? $formdata['flate_id'] :old('flate_id'); ?>

            <form action="{{url('\report\ledger')}}" method="POST">
                @csrf
                <x-input type="date" name="fromdate" id="fromdate" label="From Date" :value="$frommdate" errorname="fromdate"/>
                <x-input type="date" name="todate" id="todate" label="To Date" :value="$todate" errorname="todate"/>
                <x-select name="account_id" id="account_id" label="Account Name" :options="$accounts" :selected="$account_id" errorname="account_id" />
                <x-select name="flate_id" id="flate_id" label="Flate No" :options="$flates" :selected="$flate_id" errorname="flate_id"/>
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
                <th class="text-end">Dr Amount</th>
                <th class="text-end">Cr Amount</th>
                <th class="text-end">Balance</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $rowNumber = 0; 
                @endphp
                <?php  
                //echo "<pre>";  print_r($data); echo "</pre>";              
                ?>
                @if($data)
                @foreach($data as $key => $row)         
                <tr>
                    
                    <td>{{$key > 0 && $row->no === $data[$key - 1]->no ? ' ' : $rowNumber+1}}</td>
                    <td>{{($key > 0 && $row->no === $data[$key - 1]->no) || $row->date == 0 ? '-' : \Carbon\Carbon::parse($row->date)->format('d-m-Y') }}</td>
                    <td>{{$key > 0 && $row->no === $data[$key - 1]->no ? '-' : $row->no}}</td>
                    <td>{{$key > 0 && $row->no === $data[$key - 1]->no ? '-' : $row->type}}</td>
                    <td>{{$row->name}}</td>
                    <td><a href="{{ url("flate/{$row->flate_id}/edit")}}">@if($key > 0)<span class="badge text-bg-primary">{{$row->flate}}</span></a>@endif</td>
                    <td class="text-end">{{number_format($row->DR,2)}}</td>
                    <td class="text-end">{{number_format($row->CR,2)}}</td>
                    <?php $bal =$key == 0 ? $data[$key]->combal = 0 + $row->DR - $row->CR : $data[$key]->combal = $data[$key-1]->combal + $row->DR - $row->CR ?>
                    <td class="text-end">{{number_format($bal, 2)}}</td>
                </tr>
                @endforeach

                <?php
                    // echo "<pre>"; 
                    // print_r($data);
                    // echo "</pre>";
                ?>
                @endif
            </tbody>
        </table>
        </div>
        <div class="card-footer">

        </div>
    </div>
</div>
@endsection