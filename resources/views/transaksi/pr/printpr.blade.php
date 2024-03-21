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
            font-size:10px;
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
            background-color: #f7f3f3;
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
        }

        #tblheader{
            border: 1px solid black;
            border-collapse: collapse;
            border-spacing: 0px;
            padding-top: 0;
        }

        th, td { padding: 2px; }

    </style>
</head>
<body>
    <table id="tblheader" style="width: 100%;" cellspacing="0" cellpadding="0">
        <tr>
            <th  style="text-align:left; width:100px;">
                @if(checkIsLocalhost())
                    <img src="{{ public_path('/assets/img/logo.jpg') }}" class="img-thumbnail" alt="Logo" style="width:250px; height:35px;margin-top:0px;">
                @else
                    <img src="{{ asset(getCompanyLogo()) }}" class="img-thumbnail" alt="Logo" style="width:250px; height:35px;margin-top:0px;">
                @endif
            </th>
            <th colspan="2" style="text-align:right; font-family: Arial, Helvetica, sans-serif; margin-right:10px;">
                <i style="margin-right:40px; font-size:12px;">NO : {{ $prhdr->prnum }}</i>
            </th>
        </tr>
        <tr>
            <th colspan="3">
                <u>SURAT PERMINTAAN BARANG</u>
            </th>
        </tr>
        <tr>
            <th colspan="3" style="text-align:center; font-family: Arial, Helvetica, sans-serif; margin-right:50px;font-size:11px;">
                <i>PROYEK : {{ $project }}</i>
            </th>
        </tr>
        <tr>
            <th style="width: 450px;"></th>
            <th style="width: 50px; text-align:left; font-weight:normal; margin-left:10px; font-size:10px;">
                Kepada:
            </th>
            <th style="text-align:left; font-weight:normal; margin-left:10px; font-size:10px;">
                <li>Pengadaan & Peralatan Pusat</li>
            </th>
        </tr>
        <tr>
            <th style="width: 450px;"></th>
            <th style="width: 50px; text-align:left; font-weight:normal; margin-left:10px; font-size:10px;">
            </th>
            <th style="text-align:left; font-weight:normal; margin-left:10px; font-size:10px;">
                <li>Pengadaan & Peralatan Proyek</li>
            </th>
        </tr>
        <tr>
            <th colspan="3" style="text-align:left; font-size:10px; font-weight:normal; margin-left:10px;">
                Mohon dibelikan / dipesan segera barang-barang tersebut dibawah ini :
            </th>
        </tr>
    </table>


    <!-- <br> -->
    <table id="items">
        <thead font-weight:bold; font-size:9px;>
            <th>NO</th>
            <th style="width:200px; text-align:center">NAMA BARANG</th>
            <th style="width:200px; text-align:center">SPEK / TYPE / UKURAN</th>
            <th style="text-align:center;">JUMLAH</th>
            <th style="text-align:center;">SATUAN</th>
            <th style="text-align:center;">TIBA DI PROYEK</th>

        </thead>
        <tbody style="font-weight:normal; margin-left:10px; font-size:10px;">
            @foreach ($dtlGroup as $val => $grp)
                <tr>
                    <td></td>
                    <td><b><i>{{ $grp->itemtext }}</i></b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach($pritem as $key => $row)
                    @if($grp->itemtext === $row->itemtext)
                        <tr>
                            <td style="text-align: center;">{{ $key+1 }}</td>
                            <td>{{ $row->matdesc }}</td>
                            <td>{{ $row->matspec }}</td>
                            <td style="text-align:center;">
                            @if(strpos($row->quantity, '.000') !== false)
                            {{ number_format($row->quantity, 0, ',', '.') }}
                            @else
                            {{ number_format($row->quantity, 3, ',', '.') }}
                            @endif

                            </td>
                            <td style="text-align:center;">{{ $row->unit }}</td>
                            <td></td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>

    <table id="tblheader" style="width: 100%; font-family: Arial, Helvetica, sans-serif;" cellspacing="0" cellpadding="0">
        <tr>
            <th  style="text-align:center; width:100px; font-size:10px; font-weight:normal;">

            </th>
            <th  style="text-align:center; width:100px; font-size:10px; font-weight:normal;">

            </th>
            <th  style="text-align:center; width:100px; font-size:10px; font-weight:normal;">
                {{-- <b><i>Handil, 15 Desember 2023</i></b> --}}
            </th>
        </tr>
        <tr>
            <th  style="text-align:center; width:100px; font-size:10px; font-weight:normal;">
                Disetujui Oleh,
            </th>
            <th  style="text-align:center; width:100px; font-size:10px; font-weight:normal;">
                Diketahui Oleh,
            </th>
            <th  style="text-align:center; width:100px; font-size:10px; font-weight:normal;">
                Dibuat Oleh,
            </th>
        </tr>
        <tr>
            <th  style="text-align:center; width:100px; font-size:10px; font-weight:normal;">

            </th>
            <th  style="text-align:center; width:100px; font-size:10px; font-weight:normal;">

            </th>
            <th  style="text-align:center; width:100px; font-size:10px; font-weight:normal;">
                <br><br><br>
            </th>
        </tr>
        <tr>
            <th  style="text-align:center; width:100px; font-size:10px; font-weight:normal;">
                <b><u><i>{{ $proyek->project_manager ?? '' }}</i></u></b>
            </th>
            <th  style="text-align:center; width:100px; font-size:10px; font-weight:normal;">
                <b><u><i>{{ $proyek->manager_lapangan ?? '' }}</i></u></b>
            </th>
            <th  style="text-align:center; width:100px; font-size:10px; font-weight:normal;">
                <b><u><i>{{ getUserNameByID($prhdr->createdby) }}</i></u></b>
            </th>
        </tr>
        <tr>
            <th style="text-align:center; width:100px; font-size:10px; font-weight:normal;">
                {{-- <i>{{ $disetjui->jabatan }}</i> --}}
                <i>Manager Proyek</i>
            </th>
            <th  style="text-align:center; width:100px; font-size:10px; font-weight:normal;">
                {{-- <i>{{ $diktahui->jabatan }}</i> --}}
                <i>Manager Lapangan</i>
            </th>
            <th  style="text-align:center; width:100px; font-size:10px; font-weight:normal;">
                <i>{{ getUserJabatan() ?? null }}</i>
            </th>
        </tr>
    </table>
    <table style="font-size:10px; font-weight:normal;">
        <tr>
            <td><b><i>FOR/AA/PSD/021</i></b></td>
        </tr>
    </table>

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
