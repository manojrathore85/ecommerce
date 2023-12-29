@extends('layout.authapp')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4>AccountLedger</h4>

            </div>
            <div class="card-body">
                <div class="row">
                    <form action="{{ url('report/account-ledger') }}" method="POST">
                        @csrf
                        <?php
                        $fromdate = isset($formdata['fromdate']) ? $formdata['fromdate'] : old('fromdate');
                        $todate = isset($formdata['todate']) ? $formdata['todate'] : old('todate');
                        $account_id = isset($formdata['account_id']) ? $formdata['account_id'] : old('account_id');
                        ?>
                        <x-input type="date" name="fromdate" id="fromdate" label="From Date" :value="$fromdate"
                            errorname="fromdate" />
                        <x-input type="date" name="todate" id="todate" label="To Date" :value="$todate"
                            errorname="todate" />
                        <x-select name="account_id" id="account_id" label="Account" :options="$accounts" :selected="$account_id"
                            errorname="account_id" />
                        <button class="btn btn-primary mb-2 float-end">Search</button>+
                    </form>
                    <hr>
                </div>
                <div class="row">
                    <table id="table-journal" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>sno.</th>
                                <th>Date</th>
                                <th>No</th>
                                <th>Type</th>
                                <th>Account Name</th>
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
                            // echo "<pre>";  print_r($data); echo "</pre>";
                            ?>
                            @if ($data)
                                @foreach ($data as $key => $row)
                                    <tr>
                                        <td>{{ $key > 0 && $row->no === $data[$key - 1]->no ? ' ' : $rowNumber++ }}
                                        </td>
                                        <td>{{ ($key > 0 && $row->no === $data[$key - 1]->no) || $row->date == 0 ? ' ' : \Carbon\Carbon::parse($row->date)->format('d-m-Y') }}
                                        </td>
                                        <td>
                                            @if ($key > 0 && $row->no === $data[$key - 1]->no)
                                                '-';
                                            @elseif($key > 0)
                                                <a
                                                    href="{{ url("voucher/{$row->voucher_id}/edit") }}">{{ $row->no }}</a>
                                            @endif
                                        </td>
                                        <td>{{ $key > 0 && $row->no === $data[$key - 1]->no ? ' ' : $row->type }}</td>
                                        <td>
                                            @if ($key > 0)
                                                {{ $row->name }}
                                            @endif
                                        </td>
                                        <td class="text-end">{{ number_format($row->DR, 2) }}</td>
                                        <td class="text-end">{{ number_format($row->CR, 2) }}</td>
                                        <?php $bal = $key == 0 ? ($data[$key]->combal = 0 + $row->DR - $row->CR) : ($data[$key]->combal = $data[$key - 1]->combal + $row->DR - $row->CR); ?>
                                        <td class="text-end">{{ number_format($bal, 2) }}</td>
                                    </tr>
                                @endforeach

                                <?php
                                // echo "<pre>";
                                // print_r($data);
                                // echo "</pre>";
                                ?>
                            @endif
                        </tbody>
                        <tfooter>

                        </tfooter>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $data ? $data->links() . 'Page' : 'Paging Nation' }}
            </div>
        </div>
    </div>
@endsection
