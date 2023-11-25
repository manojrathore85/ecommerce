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
            <a href="/voucher/create" type="button" class="btn btn-primary float-end add-button">Add</a>
            <H3>Voucher List</H3>
        </div>
        <div class="card-body" style="overflow-x:auto;">
            <table id="voucher-table" class="table table-striped table-bordered">
                <thead>
                    <th>Sn</th>
                    <th>Date</th>
                    <th>No</th>
                    <th>Account</th>
                    <th>Amount</th>
                    <th>Naration</th>
                    <th>CreatedAt</th>
                    <th>UpdateAt</th>
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
    var voucherTable; //delear out side the function to make it accessable on every here in the page.
    $(document).ready(function() {
        voucherTable = $('#voucher-table').DataTable({
            serverSide: true, // Enable server-side processing
            processing: true, // Show processing indicator
            ajax: {
                url: '/voucher', // Replace with the correct route to fetch data
                type: 'GET',
            },
            columns: [{
                    className: 'dt-control',
                    orderable: false,
                    data: null,
                    defaultContent: ''
                    // "data": null,
                    // "name": 'Sn',
                    // "orderable": false, // Prevent sorting on this column
                    // "searchable": false, // Hide the search bar for this column
                    // "render": function(data, type, full, meta) {
                    //     return meta.row + meta.settings._iDisplayStart + 1; //to crate a sn column 
                    // }
                },
                {
                    data: 'date',
                    name: 'Date'
                },
                {
                    data: 'no',
                    name: 'No'
                },
                {
                    data: 'account_id',
                    name: 'Account'
                },
                {
                    data: 'amount',
                    name: 'Amount'
                },
                {
                    data: 'naration',
                    name: 'Naration'
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
                    name: 'action',
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
        $('#voucher-table').on('click', '.edit-button', function() {
            console.log(this);
            var id = $(this).data('id');
            $('#modaladd .modal-body').load("/group/" + id + "/edit");
            $('#modaladd').modal('show');
        });
        // Add event listener for opening and closing details
        $('#voucher-table tbody').on('click', 'td.dt-control', function() {
            console.log('asdfasdfasdfasdfasdf');
            var tr = $(this).closest('tr');
            var row = voucherTable.row(tr);
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
            } else {
                // Open this row
                //row.child(result).show();
                async function test() {
                    try {
                        const res = await getData(row.data());
                        console.log(res.data);
                        let htmlr = darawTable(res.data)
                        console.log(htmlr);
                        row.child(htmlr).show();
                    } catch (err) {
                        console.log(err);
                        row.child(err).show();
                    }
                }
                test();
            }
        });
        //Table created
        $('#voucher-table').on('click', '.delete-button', function() {
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
                        method: 'POST',
                        url: '/voucher/delete',
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
                            voucherTable.ajax.reload();
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

    function getData(d) {
        return $.ajax({
            url: '/voucherdetail/' + d.id,
            type: 'GET',
        });
    };   
    function darawTable(data) {
        //console.log(data);
        var txt = '<div class="table"> <div class="table-row" style="font-weight: bold;">' +
                    '<div class="cell">Sno.</div>' +
                    '<div class="cell">Name</div>' +
                    '<div class="cell">Flate No</div>' +
                    '<div class="cell">Owner Name</div>' +
                    '<div class="cell">M. Area</div>' +
                    '<div class="cell">Amount</div>' +
                    '<div class="cell">Naration</div>' +
                   
                    '</div>';


        let i =0;
        data.forEach(function(value, index, array) {
            i++;
            console.log(value);
            console.log(array);
            txt += '<div class="table-row">' +
                    '<div class="cell">'+ i +'</div>' +
                    '<div class="cell">'+ value.name +'</div>' +
                    '<div class="cell">'+ value.flate_no +'</div>' +
                    '<div class="cell">' + value.owner_name + '</div>' +
                    '<div class="cell">'+ value.maintenance_area +' </div>' +
                    '<div class="cell">' + value.amount + '</div>' +
                    '<div class="cell">'+ value.naration +'</div>' +
                   
                    '</div>';
        });
        txt += '</div>';
        //console.log(txt);
        return txt;
    }
</script>
<style>
    .table {
    display: table;
    width: 100%;
    border-collapse: collapse;
}

.table .table-row {
    display: table-row;
}

.table .cell{
    display: table-cell;
    border: 1px solid rgb(0 0 0 / 18%);
    padding: 8px;
}
/* #voucher-table_wrapper{
 border: solid 1px rgb(0 0 0 / 18%);   
} */
</style>
@endpush