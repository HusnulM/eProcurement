<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Document Printout</title>
	<style>
        .customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            font-size:12px;
            font-weight:bold;
            margin-bottom:5px;
        }

        .customers td, .customers th {
            /* border: 1px solid #000; */
            /* padding: 5px; */
        }

        /* .customers tr:nth-child(even){background-color: #f2f2f2;}

        .customers tr:hover {background-color: #ddd;} */

        .customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            color: black;
        }

        #items {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            font-size:12px;
        }

        #items td, #items th {
            border: 1px solid #000;
            padding: 3px;
        }

        /* #items tr:nth-child(even){background-color: #f2f2f2;} */

        /* #items tr:hover {background-color: #ddd;} */

        #items th {
            /* padding-top: 12px;
            padding-bottom: 12px; */
            text-align: left;
            background-color: #B4B1B1;
            color: black;
        }
    </style>
</head>
<body> 
    <h2 style="text-align:center; font-family: Arial, Helvetica, sans-serif;">RECEIPT PURCHASE ORDER</h2>
    <table border="0" cellspacing="0" cellpadding="0" class="customers" style="margin-bottom: 20px !important;">
        <tr>
            <td style="width:120px;">Receipt Number</td>
            <td style="width:20px;">:</td>
            <td>{{ $pohdr->docnum }}</td>
            <td style="width:90px;">Receipt Date</td>
            <td style="width:10px;">:</td>
            <td style="width:150px;">{{ \Carbon\Carbon::parse($pohdr->postdate)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td>Received By</td>
            <td>:</td>
            <td>{{ $pohdr->received_by }}</td>
            <td>Vendor</td>
            <td>:</td>
            <td>{{ $pohdr->vendor_name }}</td>
        </tr>
        <tr>
            <td>Remark</td>
            <td>:</td>
            <td>{{ $pohdr->remark }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <!-- <br> -->
    <table id="items">
        <thead>
            <th>No</th>
            <th style="width:120px;">Part Number</th>
            <th style="width:300px;">Description</th>
            <th style="text-align:right;">Quantity</th>
            <th style="text-align:center;">Unit</th>
            <th style="text-align:right;">Unit Price</th>
            <th style="text-align:right;">Total Price</th>
        </thead>
        <tbody>
            @foreach($poitem as $key => $row)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $row->material }}</td>
                <td>{{ $row->matdesc }}</td>
                <td style="text-align:right;">
                @if(strpos($row->quantity, '.000') !== false)
                {{ number_format($row->quantity, 0, ',', '.') }}
                @else
                {{ number_format($row->quantity, 3, ',', '.') }}
                @endif                
                </td>
                <td style="text-align:center;">{{ $row->unit }}</td>
                <td style="text-align:right;">
                @if(strpos($row->unit_price, '.000') !== false)
                {{ number_format($row->unit_price, 0, ',', '.') }}
                @else
                {{ number_format($row->unit_price, 3, ',', '.') }}
                @endif                
                </td>
                <td style="text-align:right;">
                @if(strpos($row->total_price, '.000') !== false)
                {{ number_format($row->total_price, 0, ',', '.') }}
                @else
                {{ number_format($row->total_price, 3, ',', '.') }}
                @endif                
                </td>
                
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>