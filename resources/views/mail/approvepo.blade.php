<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Approve PO</title>

    <style>
        #customers {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        }

        #customers td, #customers th {
        border: 1px solid #ddd;
        padding: 8px;
        }

        #customers tr:nth-child(even){background-color: #f2f2f2;}

        #customers tr:hover {background-color: #ddd;}

        #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #04AA6D;
        color: white;
        }
    </style>
</head>
<body>
    <div class="table-responsive">
        <p>Mohon untuk Approve/Reject PO berikut :</p><br>
        <table id="customers" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
            <thead>
                <th>No</th>
                <th>Nomor PO</th>
                <th>Tanggal PO</th>
                <th>Material</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
                <th>Created By</th>
            </thead>
            <tbody>
                @foreach ($data as $key => $row)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $row->ponum }}</td>
                    <td>{{ $row->podat }}</td>
                    <td>{{ $row->material }}</td>
                    <td>{{ $row->matdesc }}</td>
                    <td style="text-align: right;">{{ number_format($row->quantity,0) }} {{ $row->unit }}</td>
                    <td style="text-align: right;">{{ number_format($row->price,0) }}</td>
                    <td style="text-align: right;">{{ number_format($row->quantity*$row->price,0) }}</td>
                    <td>{{ $row->createdby }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <br>
        <a href="{{ url('approve/po/detail') }}/{{ $poid }}" target="_blank" >
            Approve / Reject PO
        </a>
    </div>
</body>
</html>