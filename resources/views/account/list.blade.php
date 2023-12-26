@extends('layout.authapp')
@section('content')
<div class="container">
    @csrf
    @component('components.modalbox')
    @slot('modalid')
    modaladd
    @endslot
    @slot('title')
    Account
    @endslot
    @slot('buttons')

    @endslot
    @endcomponent
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-primary float-end add-button">Add</button>
            <h1> Accounts</h1>
        </div>
        <div class="card-body">
            <table id="account-table" class="table table-striped table-responsive">
                <thead>
                    <tr>
                        <th>Sno.</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Eamil</th>
                        <th>city</th>
                        <th>Group</th>
                        <th>Nature</th>
                        <th>Opening</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
    var accountTable;
    $(document).ready(function() {
        accountTable = $('#account-table').DataTable({
            serverSide: true,
            processing: true,
            responsive: true,
            ajax: {
                url: '{{url("/account")}}',
                type: 'GET',
            },
            columns: [{
                    data: null,
                    name: 'Sno',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        //return meta.row + meta.setting._iDisplayStart + 1 // to create a sereal no column    
                        return meta.row + meta.settings._iDisplayStart + 1; //to crate a sn column  
                    }
                },
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'phone',
                    name: 'phone',
                },
                {
                    data: 'email',
                    name: 'email',
                },
                {
                    data: 'cityname',
                    name: 'city',
                },
                {
                    data: 'groupname',
                    name: 'group',
                },
                {
                    data: 'nature',
                    name: 'nature',
                },
                {
                    data: 'op_balance',
                    name: 'opening',
                },
                {
                    data: null,
                    name: 'Action',
                    orderable:false,
                    searchable:false,
                    render:function(data,type,row){
                        let btns = '<button data-id="'+row.id+'" class="btn btn-primary btn-small edit-button" >Edit</button>';
                        return btns += '<button data-id="'+row.id+'" class="btn btn-danger btn-small" >Delete</button>';  
                    }


                }

            ],
            dom: '<"row"<"col-md-6"B><"col-md-6"f>>tr<"row"<"col-md-4"l><"col-md-4"i><"col-md-4"p>>',
            buttons: ['copy', 'excel', 'pdf', 'colvis', 'reload',
                {
                    text: 'Reload',
                    action: function(e, dt, node, config) {
                        dt.ajax.reload();
                    }
                }
            ],
        });
        $('.add-button').click(function(){
            $('#modaladd .modal-body').load('{{url("/account/create")}}');
            $('#modaladd').modal('show');
        });
        $('#account-table').on('click', '.edit-button', function(){
            let id = $(this).data('id');
            console.log(id);
            $('#modaladd').modal('show');
            $('#modaladd .modal-body').load('{{url("/account/")}}/'+id+'/edit');
        });
    });
</script>
@endpush