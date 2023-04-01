<?php 
    $imagePath = public_path();
    $imagePath = str_replace("main/public","",$imagePath);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Print Purchase Order</title>
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

        .leftbox {
            float:left;
            /* background:Red; */
            width:57%;
            height:auto;
            margin-right: 2px;
        }
        /* #middlebox{
            float:left;
            background:Green;
            width:50%;
            height:280px;
        } */
        .rightbox{
            float:right;
            /* background:blue; */
            width:42%;
            height:auto;
            margin-left: 10px;
        }
            
    </style>
</head>
<body> 
    <div class="leftbox">
        {{-- <img src="{{ public_path($logo->setting_value ?? '') }}" class="img-thumbnail" alt="E-Logo" style="width:90px; height:60px;"> --}}
        <img src="{{ public_path('/assets/img/logo.png') }}" class="img-thumbnail" alt="Logo" style="width:100px; height:100px;">
        <p style="text-align:left; font-family: Arial, Helvetica, sans-serif;">
            <b>Head Office :</b></p>
        <p style="text-align:left; font-family: Arial, Helvetica, sans-serif;">
            {{ getCompanyAddress() }}
        </p>
        <table border="1" cellspacing="3" cellpadding="5" class="customers">
            <tr>
                <td>To Supplier</td>
                <td>{{ $pohdr->vendor_name }}</td>
            </tr>
            <tr>
                <td>Address</td>
                <td>Alamat Vendor</td>
            </tr>
        </table>
    </div>
    <div class="rightbox">
        <h2 style="text-align:center; font-family: Arial, Helvetica, sans-serif;">PURCHASE ORDER</h2>
        <table border="1" cellspacing="3" cellpadding="5" class="customers" style="margin-bottom: 20px !important;">
            <tr>
                <td>PO Number</td>
                <td>{{ $pohdr->ponum }}</td>
            </tr>
            <tr>
                <td>PO Date</td>
                <td>{{ \Carbon\Carbon::parse($pohdr->podat)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td>Delivery Date</td>
                <td>{{ \Carbon\Carbon::parse($pohdr->podat)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td>Term of Payment</td>
                <td>{{ $pohdr->tf_top }}</td>
            </tr>
        </table>
    </div> 
    
    <div>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <p>
            Please supply for the following goods and/ or services :
        </p>
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
                <?php 
                    $totalPrice = 0;
                    $totalPPN   = 0; 
                ?>
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

                <?php 
                    $totalPrice = $totalPrice + ($row->quantity*$row->price); 
                ?>
                @endforeach

                <?php 
                    if($pohdr->ppn > 0){
                        $totalPPN   = $totalPrice * ($pohdr->ppn / 100);
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align:right;">
                        <b> PPN</b>
                    </td>
                    <td style="text-align:right;">
                        <b>
                            {{ number_format($totalPPN, 0, ',', '.') }}
                        </b>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:right;">
                        <b> Grand Total</b>
                    </td>
                    <td style="text-align:right;">
                        <b>
                            {{ number_format($totalPrice+$totalPPN, 0, ',', '.') }}
                        </b>
                    </td>
                </tr>
            </tfoot>
        </table>

        <br>
        <table>
            <tr>
                <td>Purchasing Manager, {{ $_SERVER['DOCUMENT_ROOT'] }}</td>
            </tr>
            <tr>
                <td>
                    @if($pohdr->approvestat == "A")
                    <img src="{{ public_path(Auth::user()->s_signfile ?? '') }}" class="img-thumbnail" alt="E-sign" style="width:100px; height:100px;">
                    {{-- @if(checkIsLocalhost())
                    
                    @else
                    
                    <img src="{{ $imagePath }}{{ Auth::user()->s_signfile ?? '' }}" class="img-thumbnail" alt="E-sign" style="width:100px; height:100px;">
                    @endif --}}
                    @endif
                </td>
            </tr>
            <tr>
                <td>____________________</td>
            </tr>
        </table>
    </div>
</body>
</html>