@extends('printer_pdf.layout')
@section('content')

    <div class="row">

        <div class="col-xs-4">
             <table width="100%">
            <tr>
                <td align="left">
                    <span>Prescribed By: {{ $prescribed_by->surname." ".$prescribed_by->othername}}<br>
                    </span>
 
                </td>
                <td align="center">
                </td>
                <td align="right">
                    <span>{{ $patient->surname." ".$patient->othername }} ({{ $patient->patient_no }})
                    </span>
                </td>
            </tr>
        </table>
        </div>
        <div class="col-xs-4">
            <h3>Medical Prescriptions</h3>
            <table width="100%">
                <thead>
                <tr>
                    <th>Drug</th>
                    <th>mg/ml</th>
                    <th>Directions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($prescriptions as $row)
                    <tr>
                        <td>{{ $row->drug }}</td>
                        <td> {{ $row->qty }}</td>
                        <td>{{ $row->directions }}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection
