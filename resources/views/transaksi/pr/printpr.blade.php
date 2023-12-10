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
    <table cellspacing="0" cellpadding="0">
        <tr>
            <td style="text-align:center; width:130px;" rowspan="3">
                <img src="{{ asset(getCompanyLogo()) }}" class="img-thumbnail" alt="E-Logo" style="width:90px; height:60px;">
            </td>
            <td style="text-align:center; width:500px;">
                <h2 style="text-align:center; font-family: Arial, Helvetica, sans-serif;">PURCHASE REQUISITION</h2>
            </td>
        </tr>
    </table>
    <table border="0" cellspacing="0" cellpadding="0" class="customers" style="margin-bottom: 20px !important;">
        <tr>
            <td style="width:120px;">PR Number</td>
            <td style="width:20px;">:</td>
            <td>{{ $prhdr->prnum }}</td>
            <td style="width:90px;">Department</td>
            <td style="width:10px;">:</td>
            <td style="width:150px;">{{ getDepartmentByID($prhdr->deptid) }}</td>
        </tr>
        <tr>
            <td>Requirement Date</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($prhdr->prdate)->format('d-m-Y') }}</td>
            <td>Created By</td>
            <td>:</td>
            <td>{{ getUserNameByID($prhdr->createdby) }}</td>
        </tr>
        <tr>
            <td>Remark</td>
            <td>:</td>
            <td>{{ $prhdr->remark }}</td>
            <td>Status</td>
            <td>:</td>
            <td>
                @if($prhdr->approvestat === "A")
                    Approved
                @elseif($prhdr->approvestat === "O")
                    Open
                @elseif($prhdr->approvestat === "R")
                    Rejected
                @endif
            </td>
        </tr>
        <tr>
            <td>Project</td>
            <td>:</td>
            <td>{{ $project ?? '' }}</td>
        </tr>
    </table>
    <!-- <br> -->
    <table id="items">
        <thead>
            <th>No</th>
            <th style="width:100px;">Part Number</th>
            <th style="width:200px;">Description</th>
            <th style="text-align:right;">Quantity</th>
            <th style="text-align:center;">Unit</th>
            <th>PBJ Number</th>
            <th>PBJ Remark</th>
            <th>Periode Budget</th>
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
                <td>{{ $row->pbjnumber }}</td>
                <td>{{ $row->remarkpbj }}</td>
                <td>{{ $row->periode }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <table class="table">
        <tr>
            <td style="width:15%;">Dibuat Oleh,</td>
            <td style="width:450px;"></td>
            <td style="width:15%;">Approve Oleh,</td>
        </tr>
        <tr>
            <td>
                <img src="{{ asset($creatorSign->s_signfile) }}" class="img-thumbnail" alt="E-sign" style="width:100px; height:100px;">
            </td>
            <td></td>
            <td>
                @if($approveSign)
                <img src="{{ asset($approveSign->s_signfile ?? '') }}" class="img-thumbnail" alt="E-sign" style="width:100px; height:100px;">
                @else
                <br>
                @endif
            </td>
        </tr>
        <tr>
            <td> <u> {{ getUserNameByID($prhdr->createdby) }} </u></td>
            <td></td>
            <td>
                @if($approval)
                <u> {{ getUserNameByID($approval->approved_by) }} </u><br>
                Date: {{ formatDate($approval->approval_date ?? null) }}
                @endif
            </td>
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
