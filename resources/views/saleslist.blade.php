<!DOCTYPE html>
<html>

<head>
    <title>
        Calader Selection
    </title>
    <link href="{{ asset('public/css/bootstrap-datepicker.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- datatable  --}}
    <link href="https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.js"></script>
    {{-- datepicker --}}
    <script src="{{ asset('public/js/bootstrap-datepicker.js') }}"></script>
    {{-- datatable-rowgrouping --}}
    <script src="{{ asset('public/js/dataTables.rowsGroup.js') }}"></script>
</head>

<body>
    <div class="row">
        <div class="col-12 text-center" <input type="checkbox" name="su" id="su" value="0"
            class="days">Sunday
            <input type="checkbox" name="mo" id="mo" value="1" class="days">Monday
            <input type="checkbox" name="tu" id="tu" value="2" class="days">Tuesday
            <input type="checkbox" name="we" id="we" value="3" class="days">Wednesday
            <input type="checkbox" name="th" id="th" value="4" class="days">Thursday
            <input type="checkbox" name="fr" id="fr" value="5" class="days">Friday
            <input type="checkbox" name="st" id="st" value="6" class="days">Saturday
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center" id="datepicker">
            <label for="fromdate">From Date</label>
            <input type="text" name="fromdate" id="fromdate" class="datepicker"
                value="{{ Carbon\Carbon::now()->format('d-m-Y') }}">
            <label for="todate">Todate </label>
            <input type="text" name="todate" id="todate" class="datepicker"
                value="{{ Carbon\Carbon::now()->format('d-m-Y') }}">
        </div>
    </div>
    <table id="salesTable" class="table table-bordered">
        <thead>
            <tr>
                <th>user</th>
                <th>Product</th>
                <th>Model</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>amount</th>
            </tr>
        </thead>
    </table>
    </form>
    <script>
        var salesTable = $("#salesTable").DataTable({

            columns: [{
                    name: 'saleby',
                    data: 'saleby',
                    title: 'UserName',
                },
                {
                    name: 'product_name',
                    data: 'product_name',
                    title: 'product',
                },
                {
                    name: 'model_name',
                    data: 'model_name',
                    title: 'Model',
                },
                {
                    name: 'qty',
                    data: 'qty',
                    title: 'Qty',
                },
                {
                    name: 'rate',
                    data: 'rate',
                    title: 'Rate',
                },
                {
                    name: 'amount',
                    data: 'amount',
                    title: 'Amount',
                },
            ],
            ajax: {
                url: '{{ url('/sales') }}',
                type: 'GET',
                data: function(d) {
                    d.fromdate = $("#fromdate").val();
                    d.todate = $("#todate").val();
                },
            },
            rowsGroup: [0, 1],
            pageLength: '20',
        });

        $(".datepicker").change(function() {
            $("#salesTable").DataTable().ajax.reload();
        })
        $(".datepicker").datepicker({
            format: "dd-mm-yyyy",
        });
        $(".days").change(function() {
            var days = [0, 1, 2, 3, 4, 5, 6];
            $(".days").each(function() {
                if ($(this).is(":checked")) {
                    let value = $(this).val();
                    //console.log(value);
                    let index = days.indexOf(parseInt(value));
                    //console.log(index);
                    if (index > -1) {
                        days.splice(index, 1);
                    }
                    //console.log(days);                    
                }
            });
            //console.log(days.toString());
            $(".datepicker").datepicker('destroy').datepicker({
                format: "dd-mm-yyyy",
                daysOfWeekDisabled: days.toString(),
            });

        });
    </script>
</body>

</html>
