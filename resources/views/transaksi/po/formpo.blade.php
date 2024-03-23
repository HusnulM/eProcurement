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
            /* background-color: #B4B1B1; */
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

        .tblheader{
            border: 1px solid black;
            border-collapse: collapse;
            border-spacing: 0px;
            padding-top: 0;
            width: 100%;
        }

        .tblheader td, .tblheader th {
            border: 1px solid #000;
            padding: 3px;
        }

        .tblSubDtl{
            border: 0px solid black;
            border-collapse: collapse;
            border-spacing: 0px;
            padding-top: 0;
            width: 100%;
        }

        .tblSubDtl td, .tblheader th {
            border: none #000;
            padding: 0px;
        }

    </style>
</head>
<body>
    <table class="tblheader">
        <tr>
            <td rowspan="3" style="width:300px;">
                @if(checkIsLocalhost())
                    <img src="{{ public_path('/assets/img/logo.jpg') }}" class="img-thumbnail" alt="Logo" style="width:150px; height:28px;margin-top:0px; text-align:left;">
                @else
                    <img src="{{ asset(getCompanyLogo()) }}" class="img-thumbnail" alt="Logo" style="width:250px; height:35px;margin-top:0px; text-align:left;">
                @endif <br>
                <center><b style="text-align:center; font-family: Arial, Helvetica, sans-serif; font-size:14px;">PURCHASE ORDER</b></center>
            </td>
            <td style="text-align:left; font-family: Arial, Helvetica, sans-serif; width:50px; font-size:12px;">PO No</td>
            <td style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                {{ $pohdr->ponum }}
            </td>
        </tr>
        <tr>
            <td style="text-align:left; font-family: Arial, Helvetica, sans-serif; width:50px; font-size:12px;">DATE</td>
            <td style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                {{-- $date = Carbon\Carbon::now()->format('j-f-Y'); --}}
                {{ \Carbon\Carbon::parse($pohdr->podat)->format('F j, Y') }}
            </td>
        </tr>
        <tr>
            <td style="text-align:left; font-family: Arial, Helvetica, sans-serif; width:50px; font-size:12px;">PAGE</td>
            <td style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                {{-- {{ $pohdr->ponum }} --}}
            </td>
        </tr>
    </table>

    <table class="tblheader">
        <tr>
            <td rowspan="2" style="width:300px; text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; border:0px !important;">
                <table class="tblSubDtl">
                    <tr>
                        <td style="width: 50px; vertical-align:top;" ><b>TO :</b></td>
                        <td>
                            {{ $pohdr->vendor_name }} <br>
                            {{ $pohdr->vendor_address }} <br>
                            Tlp : {{ $vendor->vendor_telp }} <br>
                            Up  : {{ $vendor->contact_person }}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                <?php $ppn = $totalPrice * ($pohdr->ppn / 100) ?>
                VALUE   : <b>Rp.    {{ number_format($totalPrice+$ppn,0) }}</b>
            </td>
        </tr>
        <tr style="font-family: Arial, Helvetica, sans-serif; font-size:12px;">
            <td rowspan="">PAYMENT TERMS   7 Days After Receive Invoice</td>
        </tr>
    </table>

    <table class="tblheader">
        <tr>
            <td rowspan="3" style="width:300px; text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; border:0px !important;">
                <b>DELIVERY TO :</b> <br>
                {{ $pohdr->vendor_name }} <br>
                {{ $pohdr->vendor_address }} <br>
                Tlp : {{ $vendor->vendor_telp }} <br>
                Up  : {{ $vendor->contact_person }}
            </td>
            <td style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                <table class="tblSubDtl">
                    <tr>
                        <td style="width: 130px;" >RETENTION</td>
                        <td style="width: 10px;">:</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td colspan="" >AGREED LIQUIDATED</td>
                        <td>:</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td colspan="" >DAMAGES</td>
                        <td>:</td>
                        <td>-</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style="font-family: Arial, Helvetica, sans-serif; font-size:12px;">
            <td>
                <table class="tblSubDtl">
                    <tr>
                        <td style="width: 130px;" >DELIVERY TERMS</td>
                        <td style="width: 10px;">:</td>
                        <td>Indent 2 Hari</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style="font-family: Arial, Helvetica, sans-serif; font-size:12px;">
            <td>
                <table class="tblSubDtl">
                    <tr>
                        <td style="width: 130px;" >DELIVERY DATE</td>
                        <td style="width: 10px;">:</td>
                        <td>Indent 2 Hari</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="tblheader">
        <tr>
            <td style="width:300px; text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                <b>CLIENT : </b> PT. PHM
            </td>
            <td>
                <table class="tblSubDtl" style="width:300px; text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                    <tr>
                        <td style="width: 130px;" >INSPECTION</td>
                        <td style="width: 10px;">:</td>
                        <td>No / Certifacate</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width:300px; text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                <b>QUOTATION REF : </b> Quo Tanggal 24 Jan 24
            </td>
            <td style="text-align:center; font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                THIS ORDER MUST BE ACKNOWLEDGED
            </td>
        </tr>
    </table>
    <table id="items" style="width: 100%;">
        <thead>
            <th style="width:40px; text-align:center;">ITEM</th>
            <th style="width:50px; text-align:center;">Cost Code</th>
            <th style="width:50px;text-align:center;">Quantity</th>
            <th style="text-align:center;">Description</th>
            <th style="width:100px;text-align:center;">Unit Price</th>
            <th style="width:150px;text-align:center;">Total Price</th>
        </thead>
        <tbody>
            <?php
                $totalPrice = 0;
                $totalPPN   = 0;
            ?>
            @foreach($poitem as $key => $row)
            <tr>
                <td style="text-align:center;">{{ $key+1 }}</td>
                <td style="text-align:center;">{{ $row->costcd }}</td>
                <td style="text-align:right;">
                    {{ number_format($row->quantity, 0) }} {{ $row->unit }}
                </td>
                <td>{{ $row->matdesc }}</td>
                <td style="text-align:right;">
                    @if(strpos($row->price, '.000') !== false)
                    {{ number_format($row->price, 0) }}
                    @else
                    {{ number_format($row->price, 3) }}
                    @endif
                </td>
                <td style="text-align:right;">
                    {{ number_format($row->quantity*$row->price, 0) }}
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
                <td colspan="5" style="text-align:right;">
                    <b> Subtotal</b>
                </td>
                <td style="text-align:right;">
                    <b>
                        {{ number_format($totalPrice, 0) }}
                    </b>
                </td>
            </tr>
            <tr>
                <td colspan="5" style="text-align:right;">
                    <b> PPN</b>
                </td>
                <td style="text-align:right;">
                    <b>
                        {{ number_format($totalPPN, 0) }}
                    </b>
                </td>
            </tr>
            <tr>
                <td colspan="5" style="text-align:right;">
                    <b> Discount</b>
                </td>
                <td style="text-align:right;">
                    <b>
                        0
                    </b>
                </td>
            </tr>
            <tr>
                <td colspan="5" style="text-align:right;">
                    <b> Grand Total</b>
                </td>
                <td style="text-align:right;">
                    <b>
                        {{ number_format($totalPrice+$totalPPN, 0) }}
                    </b>
                </td>
            </tr>
        </tfoot>
    </table>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "page {PAGE_NUM} of {PAGE_COUNT}";
            $size = 10;
            $font = $fontMetrics->getFont("Verdana");
            $width = $fontMetrics->get_text_width($text, $font, $size) ;
            $x = ($pdf->get_width() - $width) ;
            $y = $pdf->get_height() - 773;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>
</html>
