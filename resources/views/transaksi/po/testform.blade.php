<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            /**
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page {
                margin: 0cm 0cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 8.4cm;
                margin-left: 30px;
                margin-right: 30px;
                /* margin-bottom: 2cm; */
            }

            /** Define the header rules **/
            header {
                position: fixed;
                top: 0.5cm;
                left: 30px;
                right: 30px;
                height: 6cm;

                /** Extra personal styles **/
                /* background-color: #03a9f4; */
                color: rgb(13, 13, 13);
                text-align: center;
                /* line-height: 1.5cm; */
            }

            /** Define the footer rules **/
            footer {
                position: fixed;
                bottom: 0.5cm;
                left: 30px;
                right: 30px;
                height: 4cm;

                /** Extra personal styles **/
                /* background-color: #03a9f4; */
                color: black;
                /* text-align: center; */
                /* line-height: 1.5cm; */
            }

            #items {
                font-family: Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 100%;
                font-size:12px;
            }

            #items td {
                /* border: 1px solid #000; */
                padding: 3px;
                border-left: 1px solid #000;
                border-right: 1px solid #000;
            }

            #items th {
                border: 1px solid #000;
                padding: 3px;
            }

            #items th {
                /* padding-top: 12px;
                padding-bottom: 12px; */
                text-align: left;
                /* background-color: #B4B1B1; */
                color: black;
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
                font-weight: normal;
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

            .noBorder {
                /* border:none !important; */
                border-left: 1px solid #000;
                border-right: 1px solid #000;
            }
        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
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
                    <td style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:10px;">
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
                    <td rowspan="">PAYMENT TERMS   {{ $pohdr->tf_top }}</td>
                </tr>
            </table>

            <table class="tblheader">
                <tr>
                    <td rowspan="3" style="width:300px; text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; vertical-align:top; border-top:none !important;">
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
                                <td>{{ $pohdr->dlv_terms }}</td>
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
                                <td>Latest {{ \Carbon\Carbon::parse($pohdr->delivery_date)->format('F j, Y') }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table class="tblheader">
                <tr>
                    <td style="width:300px; text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; border-top:none !important;">
                        <b>CLIENT : </b> PT. PHM
                    </td>
                    <td>
                        <table class="tblSubDtl" style="width:300px; text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; border-top:none !important;">
                            <tr>
                                <td style="width: 130px;" >INSPECTION</td>
                                <td style="width: 10px;">:</td>
                                <td>No / Certifacate</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="width:300px; text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; ">
                        <b>QUOTATION REF : </b> Quo Tanggal 24 Jan 24
                    </td>
                    <td style="text-align:center; font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                        THIS ORDER MUST BE ACKNOWLEDGED
                    </td>
                </tr>
            </table>
        </header>

        <footer>
            <table class="tblSubDtl" style="font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                <tr>
                    <td style="border-left:1px solid !important; border-top:1px solid !important; border-bottom:1px solid !important;">
                        REFRENCE :
                    </td>
                    <td style="border-bottom:1px solid !important; border-top:1px solid !important;">
                        Telp. 021-5363118
                    </td>
                    <td style="border-right:1px solid !important; border-top:1px solid !important; border-bottom:1px solid !important;">
                        Fax. 5363116
                    </td>
                </tr>
            </table>
            <table class="tblSubDtl" style="font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                <tr>
                    <td style="border-left:1px solid !important;">
                        ACCEPTENCE OF THIS ORDER CONSTITUTES <br>
                        AGRREMENT TO ALL THE TERM AND <br>
                        CONDITIONS THEREIN
                    </td>
                    <td style="text-align:center; border-right:1px solid !important; ">
                        <b>PT. KALIRAYA SARI</b>
                    </td>
                </tr>
                <tr>
                    <td style="border-left:1px solid !important;">
                        <table class="tblSubDtl">
                            <tr>
                                <td>SIGNED BY</td>
                                <td>:</td>
                                <td>.....................</td>
                            </tr>
                            <tr>
                                <td>ON BEHALF OF</td>
                                <td>:</td>
                                <td>
                                    {{ $pohdr->vendor_name }}
                                    + Stamp (Original)
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="text-align:center; border-right:1px solid !important;">
                        <br><br><br>
                        <u>Yonas Darja, B.Sc, M.Eng</u>
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom:1px solid !important; border-left:1px solid !important;">

                    </td>
                    <td style="text-align:center; border-bottom:1px solid !important; border-right:1px solid !important;">
                        Direktur Utama
                    </td>
                </tr>
            </table>
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            <table id="items" style="width: 100%;">
                <thead>
                    <th style="width:40px; text-align:center; ">ITEM</th>
                    <th style="width:50px; text-align:center;">Cost Code</th>
                    <th style="width:50px;text-align:center; ">Quantity</th>
                    <th style="text-align:center; ">Description</th>
                    <th style="width:100px;text-align:center; ">Unit Price</th>
                    <th style="width:150px;text-align:center; ">Total Price</th>
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
                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;"></td>
                        <td></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;"></td>
                        <td></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;"></td>
                        <td></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;"></td>
                        <td><u>Note :</u></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;"></td>
                        <td></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;"></td>
                        <td></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;"></td>
                        <td></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;"></td>
                        <td></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;"></td>
                        <td></td>
                        <td style="text-align:center;">Subtotal</td>
                        <td style="text-align:right;">
                            <b>
                                {{ number_format($totalPrice, 0) }}
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;"></td>
                        <td></td>
                        <td style="text-align:center;">PPN</td>
                        <td style="text-align:right;">
                            <b>
                                {{ number_format($totalPPN, 0) }}
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;"></td>
                        <td></td>
                        <td style="text-align:center;">Discount</td>
                        <td style="text-align:right;">
                            <b>
                               0
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center; "></td>
                        <td style="text-align:right;"></td>
                        <td style=""></td>
                        <td style="text-align:center; "><b>Total</b></td>
                        <td style="text-align:right;">
                            <b>
                                {{ number_format($totalPrice+$totalPPN, 0) }}
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;"></td>
                        <td></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;"></td>
                        <td></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;"></td>
                        <td></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;"></td>
                        <td></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;">
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right; vertical-align:top;"></td>
                        <td>
                            <table class="tblSubDtl">
                                <tr>
                                    <td style="border: none !important; width:50px;">- Condt</td>
                                    <td style="border: none !important;">:</td>
                                    <td style="border: none !important;">Workshop Franco Tambun</td>
                                </tr>
                                <tr>
                                    <td style="border: none !important;">- Order</td>
                                    <td style="border: none !important;">:</td>
                                    <td style="border: none !important;">J. Dion</td>
                                </tr>
                                <tr>
                                    <td style="border: none !important; vertical-align:top;">- For</td>
                                    <td style="border: none !important; vertical-align:top;">:</td>
                                    <td style="border: none !important;">{{ $proyek->nama_project }}</td>
                                </tr>
                            </table>
                        </td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;"></td>
                        <td></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right; vertical-align:top;"></td>
                        <td>
                            <table class="tblSubDtl">
                                <tr>
                                    <td style="border: none !important; width:50px; vertical-align:top;">- P'ment</td>
                                    <td style="border: none !important; vertical-align:top;">:</td>
                                    <td style="border: none !important;">{{ $pohdr->tf_top }} A + B + C</td>
                                </tr>
                                <tr>
                                    <td style="border: none !important;"></td>
                                    <td style="border: none !important;"></td>
                                    <td style="border: none !important;">A.) Data Bank.......Cab/Branch........No. Account.......(Rp)</td>
                                </tr>
                                <tr>
                                    <td style="border: none !important;"></td>
                                    <td style="border: none !important;"></td>
                                    <td style="border: none !important;">B.) Copy Purchase Order (Ex Fax) + Signature + Stamp (Original)</td>
                                </tr>
                                <tr>
                                    <td style="border: none !important;"></td>
                                    <td style="border: none !important;"></td>
                                    <td style="border: none !important;">C.) Surat Jalan</td>
                                </tr>
                            </table>
                        </td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;">
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;"></td>
                        <td></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;"></td>
                        <td>
                            Invoice / VAT A/N <br>
                            <b>PT. Kaliraya Sari</b> <br>
                            Wisma 77 Lt. 9 <br>
                            Jl. Letjen S Parman Kav.77, Jakarta Barat <br>
                            <b>NPWP. 01.302.639.8.038.000</b>
                        </td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;"></td>
                        <td></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:right;">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center; border-bottom:1px solid !important;"></td>
                        <td style="text-align:center; border-bottom:1px solid !important;"></td>
                        <td style="text-align:right; border-bottom:1px solid !important;"></td>
                        <td style="border-bottom:1px solid !important;"></td>
                        <td style="text-align:center; border-bottom:1px solid !important;"><b></b></td>
                        <td style="text-align:right; border-bottom:1px solid !important;"></td>
                    </tr>
                </tbody>
            </table>
        </main>
        <script type="text/php">
            if (isset($pdf)) {
                $text = "page {PAGE_NUM} of {PAGE_COUNT}";
                $size = 9;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) ;
                $x = ($pdf->get_width() - $width) / 1.44;
                $y = $pdf->get_height() - 791;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>
    </body>
</html>
