@extends('layout.authapp')
@section('content')
<div class="container">
    <div class="card">
    <form method="POST" name="formVoucher" id="formVoucher"
        @if($voucher)
        action="{{ url("voucher1/{$voucher[0]->vid}/update") }}"> 
        
        @else
        action="{{ url('voucher1') }}" >
        @endif
        <div class="card-header">          
            <a href="/voucher" class="btn btn-primary float-end" >List</a>
            <H1>Voucher</H1>
        </div>
        <div class="card-body">
                <?php //echo "<pre>"; print_r($voucher); echo "</pre>";  ?>     
                @csrf
                <div>
                    <x-input type='date' name="date" id="date" class="form-control" label="Date" value="{{$voucher ? $voucher[0]->date : old('date')}}" errorname="date" />
                    <x-input type='text' name="type" id="type" class="form-control" label="Type" value="{{$voucher ? $voucher[0]->type : old('type')}}" errorname="type" readonly />
                    <x-select name="cr_account_id" id="cr_account_id" label="Cr Account Head" :options="$accounts" :selected=" $voucher ? $voucher[0]->cr_account_id : 0 " errorname="cr_account_id" />
                    <x-select name="dr_account_id" id="dr_account_id" label="Dr Account Head" :options="$accounts" selected=" $voucher ? $voucher[0]->dr_account_id : 0 " errorname="dr_account_id" />
                    <x-input type='text' name="amount" id="amount" label="Amount" class="numericonly" value="old('account_id')" errorname="account_id" />
                    <x-input type='text' name="naration" id="naration" class="form-control" label="Naration" value="" errorname="naration" />
                </div>
                <div style="overflow-x:auto;">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sno.</th>
                                <th>Flate no</th>
                                <th>Owner Name</th>
                                <th>Maintenance Area</th>
                                <th>Maintenance Rate</th>
                                <th>Maintenance Amount</th>
                                <th>Builtup Area</th>
                                <th>Superbuiltup Area</th>
                                <th>Flore</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>                          
                            @foreach($flates as $flate )                         
                            <tr>
                                <?php if(in_array($flate->id, array_column($voucher->toArray(),'flate_id'))){
                                        $checked = 'checked';
                                    }else{
                                        $checked = 'false';
                                    } 
                                ?>
                                <td><input type="checkbox" name="flate_id[]" id="flate_id" value="{{$flate->id}}" {{$checked}}> </td>
                                <td>{{$flate->flate_no}}
                                    <input type="text" name="vd_id[]" value="{{$voucher->vd_id}}">
                                </td>
                                <td>{{$flate->owner_name}}</td>
                                <td>{{$flate->maintenance_area}}</td>
                                <td>{{$flate->maintenance_rate}}</td>
                                <td>{{$flate->maintenance_amount}}</td>
                                <td>{{$flate->builtup_area}}</td>
                                <td>{{$flate->superbuiltup_area}}</td>
                                <td>{{$flate->flore_no}}</td>
                                <td>{{$flate->status}}</td>
                                <td>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
     
        </div>
        <div class="card-footer">
            <button class="btn btn-primary float-end m-2">Submit</button>
            <a href="/voucher" class="btn btn-secondary float-end m-2">Cancel</a>
        </div>
        </form>
    </div>

</div>
@endsection
@push('scripts')
<script type="text/javascript">
    function selectedMaintenance(){        
        if($('#cr_account_id').val() == 1){
                $('#amount').hide();
                $('#dr_account_id').val(4);
                $('#dr_account_id').prop('disabled', true);
            }else{
                $('#amount').show(); 
                $('#dr_account_id').prop('disabled', false);  
            }
    }
    $(document).ready(function() {
        selectedMaintenance();
        $('#maintenance_rate, #maintenance_area').blur(function() {
            $('#maintenance_amount').val($('#maintenance_rate').val() * $('#maintenance_area').val());
        });
        $('#cr_account_id').change(function(){
            selectedMaintenance();
        });
    });
</script>
@endpush