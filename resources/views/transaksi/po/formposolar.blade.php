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
        @if(checkIsLocalhost())
        <img src="{{ public_path('/assets/img/logo.png') }}" class="img-thumbnail" alt="Logo" style="width:100px; height:100px;">
        @else
        {{-- <img src="{{ public_path('/assets/img/logo.png') }}" class="img-thumbnail" alt="Logo" style="width:100px; height:100px;"> --}}
        <img src="{{ asset(getCompanyLogo()) }}" class="img-thumbnail" alt="E-sign" style="width:100px; height:100px;">
        @endif
        {{-- getCompanyLogo --}}
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
                <td>{{ $vendor->vendor_address }}</td>
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
            <tr>
                <td>PO Remark</td>
                <td>{{ $pohdr->note }}</td>
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
                <th style="width:90px;">Part Number</th>
                <th style="width:180px;">Description</th>
                <th style="text-align:right;">Quantity</th>
                <th style="text-align:center;">Unit</th>
                <th style="text-align:right;">Unit Price</th>
                <th style="text-align:right;">Amount</th>
                {{-- <th>PR Number</th>
                <th>PBJ Remark</th> --}}
            </thead>
            <tbody>
                <?php
                    $totalPrice = 0;
                    $totalPPN   = 0;
                ?>

                <tr>
                    <td>1</td>
                    <td></td>
                    <td>Harga Dasar Solar</td>
                    <td style="text-align:right;">
                        {{-- @if(strpos($poitem->quantity, '.000') !== false)
                        {{ number_format($poitem->quantity, 0, ',', '.') }}
                        @else
                        {{ number_format($poitem->quantity, 3, ',', '.') }}
                        @endif --}}
                    </td>
                    <td style="text-align:center;"></td>
                    <td style="text-align:right;">
                        @if(strpos($poitem->price, '.000') !== false)
                        {{ number_format($poitem->price, 2, ',', '.') }}
                        @else
                        {{ number_format($poitem->price, 2, ',', '.') }}
                        @endif
                    </td>
                    <td style="text-align:right;">
                        {{-- {{ number_format($poitem->quantity*$poitem->price, 3, ',', '.') }} --}}
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td>PPN {{ $pohdr->ppn }} %</td>
                    <td style="text-align:right;">
                        {{-- @if(strpos($poitem->quantity, '.000') !== false)
                        {{ number_format($poitem->quantity, 0, ',', '.') }}
                        @else
                        {{ number_format($poitem->quantity, 3, ',', '.') }}
                        @endif --}}
                    </td>
                    <td style="text-align:center;"></td>
                    <td style="text-align:right;">
                        {{ number_format($poitem->price * ($pohdr->ppn / 100), 2, ',', '.') }}
                    </td>
                    <td style="text-align:right;">
                        {{-- {{ number_format($poitem->quantity*$poitem->price, 3, ',', '.') }} --}}
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td>PBBKB ({{ $pohdr->solar_pbbkb }}) %</td>
                    <td style="text-align:right;">
                        {{-- @if(strpos($poitem->quantity, '.000') !== false)
                        {{ number_format($poitem->quantity, 0, ',', '.') }}
                        @else
                        {{ number_format($poitem->quantity, 3, ',', '.') }}
                        @endif --}}
                    </td>
                    <td style="text-align:center;"></td>
                    <td style="text-align:right;">
                        {{ number_format($poitem->price * ($pohdr->solar_pbbkb / 100), 2, ',', '.') }}
                    </td>
                    <td style="text-align:right;">
                        {{-- {{ number_format($poitem->quantity*$poitem->price, 3, ',', '.') }} --}}
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td>OAT</td>
                    <td style="text-align:right;">
                        {{-- @if(strpos($poitem->quantity, '.000') !== false)
                        {{ number_format($poitem->quantity, 0, ',', '.') }}
                        @else
                        {{ number_format($poitem->quantity, 3, ',', '.') }}
                        @endif --}}
                    </td>
                    <td style="text-align:center;"></td>
                    <td style="text-align:right;">
                        {{ number_format($poitem->solar_oat, 2, ',', '.') }}
                    </td>
                    <td style="text-align:right;">
                        {{-- {{ number_format($poitem->quantity*$poitem->price, 3, ',', '.') }} --}}
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td>PPN OAT ({{ $pohdr->solar_ppn_oat }}) %</td>
                    <td style="text-align:right;">
                        {{-- @if(strpos($poitem->quantity, '.000') !== false)
                        {{ number_format($poitem->quantity, 0, ',', '.') }}
                        @else
                        {{ number_format($poitem->quantity, 3, ',', '.') }}
                        @endif --}}
                    </td>
                    <td style="text-align:center;"></td>
                    <td style="text-align:right;">
                        {{ number_format($poitem->solar_oat * ($pohdr->solar_ppn_oat / 100), 2, ',', '.') }}
                    </td>
                    <td style="text-align:right;">
                        {{-- {{ number_format($poitem->quantity*$poitem->price, 3, ',', '.') }} --}}
                    </td>
                </tr>

                <?php
                    $totalPrice = $poitem->price + ($poitem->price * ($pohdr->ppn / 100)) + $poitem->price * ($pohdr->solar_pbbkb / 100) + $poitem->solar_oat + ($poitem->solar_oat * ($pohdr->solar_ppn_oat / 100));
                ?>

                <tr>
                    <td></td>
                    <td></td>
                    <td><b>Total Harga Solar</b></td>
                    <td style="text-align:right;">
                        {{-- @if(strpos($poitem->quantity, '.000') !== false)
                        {{ number_format($poitem->quantity, 0, ',', '.') }}
                        @else
                        {{ number_format($poitem->quantity, 3, ',', '.') }}
                        @endif --}}
                    </td>
                    <td style="text-align:center;"></td>
                    <td style="text-align:right;">
                        <b>{{ number_format($totalPrice, 2, ',', '.') }}</b>
                    </td>
                    <td style="text-align:right;">
                        {{-- {{ number_format($poitem->quantity*$poitem->price, 3, ',', '.') }} --}}
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td>Total Pembelian Solar</td>
                    <td style="text-align:right;">
                        @if(strpos($poitem->quantity, '.000') !== false)
                        {{ number_format($poitem->quantity, 0, ',', '.') }}
                        @else
                        {{ number_format($poitem->quantity, 3, ',', '.') }}
                        @endif
                    </td>
                    <td style="text-align:center;">Liter</td>
                    <td style="text-align:right;">

                    </td>
                    <td style="text-align:right;">
                        {{ number_format($poitem->quantity*$totalPrice, 0, ',', '.') }}
                    </td>
                </tr>


                <?php
                    if($pohdr->ppn > 0){
                        $totalPPN   = $totalPrice * ($pohdr->ppn / 100);
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align:right;">
                        <b> Subtotal</b>
                    </td>
                    <td style="text-align:right;">
                        {{ number_format($poitem->quantity*$totalPrice, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:right;">
                        <b> Delivery Cost</b>
                    </td>
                    <td style="text-align:right;">

                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:right;">
                        <b> Total</b>
                    </td>
                    <td style="text-align:right;">
                        {{ number_format($poitem->quantity*$totalPrice, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:right;">
                        <b> PPN {{ $pohdr->ppn }} %</b>
                    </td>
                    <td style="text-align:right;">

                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:right;">
                        <b> Grand Total</b>
                    </td>
                    <td style="text-align:right;">
                        {{ number_format($poitem->quantity*$totalPrice, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <br>
        <table>
            <tr>
                <td style="width:250px;">
                    @if($firstApprover)
                    {{ $firstApprover->jabatan ?? '' }},
                    @endif
                </td>
                <td style="width:30px;"></td>
                <td style="width:250px;">
                    @if($secondApprover)
                    {{ $secondApprover->jabatan ?? '' }},
                    @endif
                </td>
                <td style="width:30px;"></td>
                <td style="width:250px;">
                    @if($lastApprover)
                    {{ $lastApprover->jabatan ?? '' }},
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    @if($firstApprover)
                        @if($pohdr->approvestat == "A")
                            @if(checkIsLocalhost())
                            <img src="{{ public_path($firstApprover->s_signfile ?? '') }}" class="img-thumbnail" alt="E-sign" style="width:100px; height:100px;">
                            @else
                                @if($firstApprover->s_signfile)
                                <img src="{{ asset($firstApprover->s_signfile ?? '') }}" class="img-thumbnail" alt="E-sign" style="width:100px; height:100px;">
                                @else
                                <br><br><br>
                                @endif
                            @endif
                        @endif
                    @endif
                </td>
                <td></td>
                <td>
                    @if($secondApprover)
                        @if($pohdr->approvestat == "A")
                            @if(checkIsLocalhost())
                            <img src="{{ public_path($secondApprover->s_signfile ?? '') }}" class="img-thumbnail" alt="E-sign" style="width:100px; height:100px;">
                            @else

                                @if($secondApprover->s_signfile)
                                <img src="{{ asset($secondApprover->s_signfile ?? '') }}" class="img-thumbnail" alt="E-sign" style="width:100px; height:100px;">
                                @else
                                <br><br><br>
                                @endif
                            @endif
                        @endif
                    @endif
                </td>
                <td></td>
                <td>
                    @if($lastApprover)
                        @if($pohdr->approvestat == "A")
                            @if(checkIsLocalhost())
                            <img src="{{ public_path($lastApprover->s_signfile ?? '') }}" class="img-thumbnail" alt="E-sign" style="width:100px; height:100px;">
                            @else
                                @if($lastApprover->s_signfile)
                                    <img src="{{ asset($lastApprover->s_signfile ?? '') }}" class="img-thumbnail" alt="E-sign" style="width:100px; height:100px;">
                                @else
                                <br><br><br>
                                @endif
                            @endif
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    @if($firstApprover)
                    <u>{{ $firstApprover->name ?? '' }}</u> <br>
                    Date: {{ formatDate($firstApprovalDate->approval_date) ?? '' }}
                    @endif
                </td>
                <td></td>
                <td>
                    @if($secondApprover)
                    <u>{{ $secondApprover->name ?? '' }}</u> <br>
                    Date: {{ formatDate($secondApprovalDate->approval_date) ?? '' }}
                    @endif
                </td>
                <td></td>
                <td>
                    @if($lastApprover)
                    <u>{{ $lastApprover->name ?? '' }}</u> <br>
                    Date: {{ formatDate($lastApprovalDate->approval_date) ?? '' }}
                    @endif
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
