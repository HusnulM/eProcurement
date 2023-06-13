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
    <table cellspacing="0" cellpadding="0">
        <tr>
            <td style="text-align:center; width:130px;" rowspan="3">
                <img src="{{ asset(getCompanyLogo()) }}" class="img-thumbnail" alt="E-Logo" style="width:90px; height:60px;">
            </td>
            <td style="text-align:center;">
                <h2 style="text-align:center; font-family: Arial, Helvetica, sans-serif;">WORK ORDER</h2>
                <h4 style="text-align:center; font-family: Arial, Helvetica, sans-serif;">{{ $prhdr->description }}</h4>
            </td>
        </tr>
    </table> 
    
    <table border="0" cellspacing="0" cellpadding="0" class="customers" style="margin-bottom: 20px !important;">
        <tr>
            <td style="width:120px;">WO Number</td>
            <td style="width:20px;">:</td>
            <td>{{ $prhdr->wonum }}</td>
            <td style="width:90px;">Service Date</td>
            <td style="width:10px;">:</td>
            <td style="width:150px;">{{ \Carbon\Carbon::parse($prhdr->wodate)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td>Mekanik</td>
            <td>:</td>
            <td>{{ $prhdr->mekanik }}</td>
            <td>Warehouse</td>
            <td>:</td>
            <td>{{ $prhdr->whscode }}</td>
        </tr>
        <tr>
            <td>Nomor Kendaraan</td>
            <td>:</td>
            <td>{{ $prhdr->license_number }}</td>
            <td>Last Odo Meter</td>
            <td>:</td>
            <td>
                {{ $prhdr->last_odo_meter }}
            </td>
        </tr>
        <tr>
            <td>Issued</td>
            <td>:</td>
            <td>{{ $prhdr->issued }}</td>
            <td>Status</td>
            <td>:</td>
            <td>
                @if($prhdr->wo_status === "A")
                    Approved
                @elseif($prhdr->wo_status === "O")
                    Open
                @elseif($prhdr->wo_status === "R")
                    Rejected
                @endif
            </td>
        </tr>
        <tr>
            <td>Schedule Type</td>
            <td>:</td>
            <td>{{ $prhdr->schedule_type }}</td>
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
        </thead>
        <tbody>
            @foreach($pritem as $key => $row)
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
            </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <table>
        <tr>
            <td>Dibuat Oleh,</td>
        </tr>
        <tr>
            <td>
                <img src="{{ asset(Auth::user()->s_signfile) }}" class="img-thumbnail" alt="E-sign" style="width:100px; height:100px;">
            </td>
        </tr>
        <tr>
            <td> <u> {{ getUserNameByID($prhdr->createdby) }} </u></td>
        </tr>
    </table>
    
</body>
</html>