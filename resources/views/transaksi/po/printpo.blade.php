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
    <h2 style="text-align:center; font-family: Arial, Helvetica, sans-serif;">PURCHASE ORDER</h2>
    <table border="0" cellspacing="0" cellpadding="0" class="customers" style="margin-bottom: 20px !important;">
        <tr>
            <td style="width:120px;">PO Number</td>
            <td style="width:20px;">:</td>
            <td>{{ $pohdr->ponum }}</td>
            <td style="width:90px;">Department</td>
            <td style="width:10px;">:</td>
            <td style="width:150px;">{{ getDepartmentByID($pohdr->deptid) }}</td>
        </tr>
        <tr>
            <td>PO Date</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($pohdr->podat)->format('d-m-Y') }}</td>
            <td>Created By</td>
            <td>:</td>
            <td>{{ getUserNameByID($pohdr->createdby) }}</td>
        </tr>
        <tr>
            <td>Remark</td>
            <td>:</td>
            <td>{{ $pohdr->note }}</td>
            <td>Status</td>
            <td>:</td>
            <td>
                @if($pohdr->approvestat === "A")
                    Approved
                @elseif($pohdr->approvestat === "O")
                    Open
                @elseif($pohdr->approvestat === "R")
                    Rejected
                @endif
            </td>
        </tr>
        <tr>
            <td>Vendor</td>
            <td>:</td>
            <td>{{ $pohdr->vendor_name }}</td>
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
                    @if(strpos($row->price, '.000') !== false)
                    {{ number_format($row->price, 0, ',', '.') }}
                    @else
                    {{ number_format($row->price, 3, ',', '.') }}
                    @endif                
                </td>
                <td style="text-align:right;">
                    {{ number_format($row->quantity*$row->price, 0, ',', '.') }}            
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <table>
        <tr>
            <td>Purchasing Manager,</td>
        </tr>
        <tr>
            <td>
            <img src="{{ public_path('/assets/img/esign1.png') }}" class="img-thumbnail" alt="E-sign" style="width:100px; height:100px;">
            </td>
        </tr>
        <tr>
            <td>____________________</td>
        </tr>
    </table>
</body>
</html>