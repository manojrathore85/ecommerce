@extends('layout.authapp')
@push('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" />
@endpush
@section('content')
<!-- Modal -->
<div class="container">

    @csrf
    @component('components.modalbox')
    @slot('modalid')
    modaluseradd
    @endslot
    @slot('title')
    User Model
    @endslot

    @slot('buttons')

    @endslot
    @endcomponent


    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-primary float-end add-button ">Add</button>
            <a  href="/createdumyuser" class="btn btn-primary float-end me-2">Create10 Dumy User</a>
            <H3>UserList</H3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="users-table" class="table table-striped table-responsive" >
                    <thead>
                        <th>Sn</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Email Verified At</th>
                        <th>CreatedAt</th>
                        <th>ModifiedAt</th>
                        <th>Action2</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="toast-element"></div>
@endsection
@push('scripts')
<script>
    function handleValidationErrorsControlsToast(errors) {
        for (const fieldName in errors) {
            const errorMessage = errors[fieldName][0];
            const formField = document.getElementById(fieldName);
            showToast(fieldName, errorMessage);
        }
    }

    function showToast(title, message) {
        // Clone the toast template
        var toast = document.querySelector('#liveToast').cloneNode(true);

        // Set toast content
        //toast.querySelector('.toast-header').innerText = title;
        toast.querySelector('.toast-body').innerText = message;

        // Append the toast to the toast container (usually a div with an ID)
        document.getElementById('toast-container').appendChild(toast);

        // Initialize the toast and show it
        var bootstrapToast = new bootstrap.Toast(toast);
        bootstrapToast.show();
    }
</script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
<script>
    var userTable; //delear out side the function to make it accessable on every here in the page.
    $(document).ready(function() {
        userTable = $('#users-table').DataTable({
            serverSide: true, // Enable server-side processing
            processing: true, // Show processing indicator
            responsive: true,
            ajax: {
                url: '/getusers', // Replace with the correct route to fetch data
                type: 'GET',
            },
            columns: [{
                    "data": null,
                    "name": 'Sn',
                    "orderable": false, // Prevent sorting on this column
                    "searchable": false, // Hide the search bar for this column
                    "render": function(data, type, full, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1; //to crate a sn column 
                    }
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'email_verified_at',
                    name: 'email_verified_at'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },

                {
                    data: null,
                    name: 'action2',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {

                        let btns = '<button class="btn btn-primary edit-button" data-id="' + row.id + '">Edit</button>';
                        return btns += '<button class="btn btn-danger delete-button" data-id="' + row.id + '">Delete</button>';
                    }
                },
                // Add more columns as needed
            ],
            dom: '<"row"<"col-md-6"B><"col-md-6"f>>tr<"row"<"col-md-4"l><"col-md-4"i><"col-md-4"p>>',
            buttons: ['copy', 'excel', 'pdf', 'colvis', 'reload',
                {
                    text: 'Reload',
                    action: function(e, dt, node, config) {
                        dt.ajax.reload();
                    }
                }
            ]
        });
        $('#users-table').on('click', '.edit-button', function() {
            console.log(this);
            var id = $(this).data('id');
            $('#modaluseradd .modal-body').load("user/" + id);
            $('#modaluseradd').modal('show');
        });
        $('.add-button').click(function() {
            $('#modaluseradd .modal-body').load("user");
            $('#modaluseradd').modal('show');
        });
        $('#users-table').on('click', '.delete-button', function() {
            console.log(this);
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            var id = $(this).data('id');
            if (confirm('Are you sure to delete this record')) {
                $.ajax({
                    method: 'POST',
                    url: '/user-delete',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        // You can add other headers as needed
                    },
                    data: {
                        'id': id
                    },
                    success: function(data) {
                        console.log(data);

                        showToast('success', data.message);
                        userTable.ajax.reload();

                    },
                    error: function(data) {
                        showToast('success', data.message);
                    },
                });
            }

        });



    });
</script>
@endpush