@extends('layout.authapp')
@section('content')
<!-- Modal -->
<div class="container">

    @csrf
    @component('components.modalbox')
    @slot('modalid')
    modaladd
    @endslot
    @slot('title')
    Group Model
    @endslot

    @slot('buttons')

    @endslot
    @endcomponent


    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-primary float-end add-button">Add</button>
            <H3>Group List</H3>
        </div>
        <div class="card-body">
            <table id="groups-table" class="table table-striped">
                <thead>
                    <th>Sn</th>
                    <th>Name</th>
                    <th>ParentGroup</th>
                    <th>Order</th>
                    <th>CreatedAt</th>
                    <th>ModifiedAt</th>
                    <th>Action</th>
                </thead>
            </table>
        </div>
    </div>
</div>
<div id="toast-element"></div>
@endsection
@push('scripts')
<script>
    function handleValidationErrorsControlsToast(errors) {
        for (const fieldName in errors) {
            //alert('asdfas');
            const errorMessage = errors[fieldName][0];
            const formField = document.getElementById(fieldName);
            formField.classList.add('is-invalid');
            //console.log(formField.id);
            let span = document.getElementById(formField.id + '-error');
            //console.log(span);
            if (!span) {
                span = document.createElement('span');
                span.id = formField.id + '-error';
                span.textContent = errorMessage;
                span.classList.add('text-danger');
                formField.insertAdjacentElement('afterend', span);
            }
            span.textContent = errorMessage;
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
    var groupsTable; //delear out side the function to make it accessable on every here in the page.
    $(document).ready(function() {
        groupsTable = $('#groups-table').DataTable({
            serverSide: true, // Enable server-side processing
            processing: true, // Show processing indicator
            ajax: {
                url: '/group', // Replace with the correct route to fetch data
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
                    data: 'parent_id',
                    name: 'ParentGroup'
                },
                {
                    data: 'order',
                    name: 'Order'
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
        $('#groups-table').on('click', '.edit-button', function() {
            console.log(this);
            var id = $(this).data('id');
            $('#modaladd .modal-body').load("/group/" + id + "/edit");
            $('#modaladd').modal('show');
        });
        $('.add-button').click(function() {
            $('#modaladd .modal-body').load("/group/create");
            $('#modaladd').modal('show');
        });
        $('#groups-table').on('click', '.delete-button', function() {
            console.log(this);
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            var id = $(this).data('id');
            
                Swal.fire({
                    title: 'Are you sure to delete this record?',                   
                    showCancelButton: true,
                    confirmButtonText: 'Delete',                    
                }).then((result) => {                    
                    if (result.isConfirmed) {                       
                        $.ajax({
                            method: 'DELETE',
                            url: '/group/'+id,
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
                                //showToast('success', data.message);
                                groupsTable.ajax.reload();
                                Swal.fire(data.message, '', 'success')

                            },
                            error: function(data) {
                                Swal.fire(data.message, '', 'success')
                              //  showToast('success', data.message);
                            },
                        });
                    } else if (result.isCancelled) {
                        Swal.fire('Changes are not saved', '', 'info')
                    }
                }); 

        });


    });
</script>
@endpush