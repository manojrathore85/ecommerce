@extends('layout.authapp')
@section('content')
<div class="container">
    <div class="card">
    <form method="POST" action="{{url('/voucher')}}" name="formVoucher" id="formVoucher">
        <div class="card-header">
          
            <a href="{{url('/voucher')}}" class="btn btn-primary float-end" >List</a>
            <H4>Voucher</H4>
        </div>
        <div class="card-body">
     
                @csrf
                <div>
                    <x-input type='date' name="date" id="date" class="form-control" label="Date" value="{{old('date')}}" errorname="date" />
                    <x-input type='text' name="type" id="type" class="form-control" label="Type" value="JV" errorname="type" readonly />

                    <x-select name="cr_account_id" id="cr_account_id" label="Cr Account Head" :options="$accounts" :selected="old('cr_account_id') " errorname="cr_account_id" />
                    <x-select name="dr_account_id" id="dr_account_id" label="Dr Account Head" :options="$accounts" :selected="old('dr_account_id') " errorname="dr_account_id" />
                    <x-input type='text' name="amount" id="amount" label="Amount" class="numericonly" value="{{old('amount')}}" errorname="account_id" />
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
                                <td><input type="checkbox" name="flate_id[]" id="flate_id" value="{{$flate->id}}"> </td>
                                <td>{{$flate->flate_no}}</td>
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
            <a href="{{url('/voucher')}}" class="btn btn-secondary float-end m-2">Cancel</a>
        </div>
        </form>
    </div>

</div>
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#maintenance_rate, #maintenance_area').blur(function() {
            $('#maintenance_amount').val($('#maintenance_rate').val() * $('#maintenance_area').val());
        });
    });
</script>
@endpush