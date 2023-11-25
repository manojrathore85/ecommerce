@extends('layout.authapp')
@section('content')
<div class="container">
    <?php $options = ['NA'=>'NA','Ground' => 'Ground', 'Top' => 'Top']; ?>
    <form method="POST" action="{{$flore ? '/flore/'.$flore->id : '/flore' }}" id="formflore" name="formflore">
        @csrf
       @if($flore) @method('PUT') @endif
        <x-input type="text" name="flore_no" id="flore_no" label="Flore No." value="{{$flore ? $flore->flore_no : old('flore_no')}}" errorname="flore_no" />
        <x-input type="text" name="flore_area" id="flore_area" label="Flore Area" value="{{$flore ? $flore->flore_area : old('flore_area')}} " errorname="flore_area" />
        <x-input type="text" name="no_of_flate" id="no_of_flate" label="No Of Flate" value="{{$flore ? $flore->no_of_flate : old('no_of_flate')}}" errorname="no_of_flate" />
        <x-select name="ground_or_top" id="ground_or_top" label="Ground or Top" :options="$options" :selected=" $flore ? $flore->ground_or_top : old('ground_or_top') " errorname="ground_or_top" />

        <button type="submit" id="submit" name="submit" class="btn btn-primary m-2 float-end">Save</button>
        <a href="/flore" id="cancel" class="btn btn-secondary m-2 float-end" data-bs-dismiss="modal">Cancel</a>
    </form>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Sno.</th>
                <th>Fore no</th>
                <th>Flore Area</th>
                <th>No of Flate</th>
                <th>IsGround/Top</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($flores as $flore )
            <tr>
                <td></td>
                <td>{{$flore->flore_no}}</td>
                <td>{{$flore->flore_area}}</td>
                <td>{{$flore->no_of_flate}}</td>
                <td>{{$flore->ground_or_top}}</td>
                <td>
                    <form action="/flore/{{$flore->id}}" method="POST">@csrf @if($flore) @method('delete')@endif
                        <a href="/flore/{{$flore->id}}/edit" class="btn btn-sm btn-primary">Edit</a>
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection