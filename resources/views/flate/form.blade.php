@extends('layout.authapp')
@section('content')
<div class="container">
    <form method="POST" action="{{$flate && $flate->copy == false ? '/flate/'.$flate->id : '/flate' }}" id="formflate" name="formflate">
        @csrf
       @if($flate && $flate->copy == false ) @method('PUT') @endif
        <x-input type="text" name="flate_no" id="flate_no" label="Flate No." value="{{$flate ? $flate->flate_no : old('flate_no')}}" errorname="flate_no" />       
        <x-input type="text" name="owner_name" id="owner_name" label="Owner Name" value="{{$flate ? $flate->owner_name : old('owner_name')}}" errorname="owner_name" />
        <x-input type="text" name="maintenance_area" id="maintenance_area" label="Maintenance Area" value="{{$flate ? $flate->maintenance_area : old('maintenance_area')}}" errorname="maintenance_area" class="numericonly"/>
        <x-input type="text" name="maintenance_rate" id="maintenance_rate" label="Maintenance Rate" value="{{$flate ? $flate->maintenance_rate : old('maintenance_rate')}}" errorname="maintenance_rate" class="numericonly"/>
        <x-input type="text" name="maintenance_amount" id="maintenance_amount" label="Maintenance Rate" value="{{$flate ? $flate->maintenance_amount : old('maintenance_amount')}}" errorname="maintenance_amount" class="numericonly"  />
        <x-input type="text" name="builtup_area" id="builtup_area" label="Builtup Area" value="{{$flate ? $flate->builtup_area : old('builtup_area')}}" errorname="builtup_area" class="numericonly"/>
        <x-input type="text" name="superbuiltup_area" id="superbuiltup_area" label="Superbuiltup Area" value="{{$flate ? $flate->superbuiltup_area : old('superbuiltup_area')}}" errorname="superbuiltup_area" class="numericonly"/>
        <x-select name="flore_id" id="flore_id" label="Flore" :options="$flores" :selected=" $flate ? $flate->flore_id : old('flore_id') " errorname="flore_id" />
        <x-select name="status" id="status" label="Status" :options="$status_option" :selected=" $flate ? $flate->status : old('status') " errorname="status" />

        <button type="submit" id="submit" name="submit" class="btn btn-primary float-end m-2">Save</button>
        <a href="/flate" id="cancel" class="btn btn-secondary float-end m-2" data-bs-dismiss="modal">Cancel</a>
    </form>
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
                <td></td>              
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
                    <form action="/flate/{{$flate->id}}" method="POST">@csrf @if($flate) @method('delete')@endif
                        <a href="/flate/{{$flate->id}}/copy" class="btn btn-sm btn-primary">Copy</a>
                        <a href="/flate/{{$flate->id}}/edit" class="btn btn-sm btn-primary">Edit</a>
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('#maintenance_rate, #maintenance_area').blur(function(){
            $('#maintenance_amount').val($('#maintenance_rate').val() * $('#maintenance_area').val());
        });
    });

</script>
@endpush