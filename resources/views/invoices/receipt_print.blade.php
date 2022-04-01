@extends('printer_pdf.layout')
@section('content')
    <style type="text/css">
        .standout {
            /*font-weight:bold;*/
        }

        .text-alignment {
            text-align: right;
            margin-right: 40px;
        }

    </style>

    <div class="row">
        <table width="100%">
            <tr>
                <td align="left">
                    <span>Invoice No: {{ $invoice->invoice_no }} <br>
                        Date: {{ $invoice->created_at }}<br>
                    </span>

                </td>
                <td align="center">
                </td>
                <td align="right">
                    <span>{{ $patient->surname." ".$patient->othername }}
                    </span>
                </td>
            </tr>
        </table>
        <div class="col-xs-4">
            <table width="100%">
                <thead>
                <tr>
                    <th>Transaction</th>
                    <th class="text-alignment">Qty</th>
                    <th class="text-alignment">Unit Price</th>
                    <th class="text-alignment">Total Amount</th>
                </tr>
                </thead>
                <tbody>
                @php $due_amount=0; @endphp
                @foreach($invoice_items as $row)
                    <tr>
                        <td>{{ $row->medical_service->name." ".$row->tooth_no }}</td>
                        <td class="text-alignment">{{ $row->qty }}</td>
                        <td class="text-alignment">{{ number_format($row->price) }}</td>
                        <td class="text-alignment">{{ number_format($row->qty*$row->price) }}</td>
                    </tr>
                    @php /** @var TYPE_NAME $due_amount */
                       $due_amount+=$row->qty*$row->price; @endphp
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <hr style="border: 1px solid #b0b0b0">
                    <td class="standout" style="color: red">Total Amount</td>
                    <td></td>
                    <td></td>
                    <td class="standout text-alignment" style="color: red">{{ number_format($due_amount) }}</td>
                </tr>

                </tfoot>
            </table>
        </div>
        <div class="col-xs-4">

            <h3 style="font-size: 15px;">This Receipt</h3>
            <table width="100%">
                <thead>
                <tr>
                    <th>Payment Date</th>
                    <th>Payment method</th>
                    <th class="text-alignment">Amount</th>
                </tr>
                </thead>
                <tbody>
                @php $paid_amount=0; @endphp
                @foreach($payments as $row)
                    <tr>
                        <td>{{ $row->payment_date }}</td>
                        <td>{{ $row->payment_method }}</td>
                        <td class="text-alignment">{{ number_format($row->amount) }}</td>
                    </tr>
                    @php /** @var TYPE_NAME $paid_amount */
                       $paid_amount+=$row->amount; @endphp
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <td class="standout" style="color: red">Total Paid Amount</td>
                    <td></td>
                    <td class="standout text-alignment" style="color: red">{{ number_format($paid_amount) }}
                    </td>
                </tr>
                <tr>
                    <td class="standout" style="color: red">Outstanding Balance:</td>
                    <td></td>
                    <td class="standout text-alignment" style="color: red">{{ number_format($due_amount-$paid_amount) }}
                    </td>
                </tr>


                </tfoot>
            </table>
        </div>

    </div>
@endsection
