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

        #header,
        #footer {
        position: fixed;
        left: 0;
            right: 0;
            color: #aaa;
            font-size: 0.9em;
        }
        #header {
        top: 0;
            border-bottom: 0.1pt solid #aaa;
        }
        #footer {
        bottom: 0;
        border-top: 0.1pt solid #aaa;
        }
        .page-number:before {
        content: "Page " counter(page);
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
        <table id="items" style="width: 100%;">
            <thead>
                <th>No</th>
                <th style="width:90px;">Part Number</th>
                <th style="width:180px;">Description</th>
                <th style="text-align:right;">Quantity</th>
                <th style="text-align:center;">Unit</th>
                <th style="text-align:right;">Unit Price</th>
                <th style="text-align:right;">Total Price</th>
                <th>PR Number</th>
                <th>PBJ Remark</th>
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
                        {{ number_format($row->quantity*$row->price, 3, ',', '.') }}
                    </td>
                    <td>{{ $row->prnum }}</td>
                    <td>{{ $row->remarkpbj }}</td>
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
                    <td colspan="8" style="text-align:right;">
                        <b> Subtotal</b>
                    </td>
                    <td style="text-align:right;">
                        <b>
                            {{ number_format($totalPrice, 0, ',', '.') }}
                        </b>
                    </td>
                </tr>
                <tr>
                    <td colspan="8" style="text-align:right;">
                        <b> PPN</b>
                    </td>
                    <td style="text-align:right;">
                        <b>
                            {{ number_format($totalPPN, 0, ',', '.') }}
                        </b>
                    </td>
                </tr>
                <tr>
                    <td colspan="8" style="text-align:right;">
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
                    Date: {{ formatDate($firstApprovalDate->approval_date ?? null) }}
                    @endif
                </td>
                <td></td>
                <td>
                    @if($secondApprover)
                    <u>{{ $secondApprover->name ?? '' }}</u> <br>
                    Date: {{ formatDate($secondApprovalDate->approval_date ?? null) }}
                    @endif
                </td>
                <td></td>
                <td>
                    @if($lastApprover)
                    <u>{{ $lastApprover->name ?? '' }}</u> <br>
                    Date: {{ formatDate($lastApprovalDate->approval_date ?? null) }}
                    @endif
                </td>
            </tr>
        </table>
    </div>
    <script type="text/php">
        if (isset($pdf)) {
            $text = "page {PAGE_NUM} of {PAGE_COUNT}";
            $size = 10;
            $font = $fontMetrics->getFont("Verdana");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>
</html>
