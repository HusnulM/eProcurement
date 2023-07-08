<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Approve PR</title>

    <style>
        #customers {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        }

        #customers td, #customers th {
        border: 1px solid #ddd;
        padding: 8px;
        }

        #customers tr:nth-child(even){background-color: #f2f2f2;}

        #customers tr:hover {background-color: #ddd;}

        #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #04AA6D;
        color: white;
        }

        a:link, a:visited {
            background-color: #f44336;
            color: white;
            padding: 14px 25px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        a:hover, a:active {
            background-color: rgb(0, 145, 255);
        }
    </style>
</head>
<body>
    <div class="table-responsive">
        <p>Mohon untuk Approve/Reject PR berikut :</p><br>
        <table id="customers" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
            <thead>
                <th>No</th>
                <th>Nomor PR</th>
                <th>Tanggal PR</th>
                <th>No. Plat</th>
                <th>Material</th>
                <th>Description</th>
                <th>Department</th>
                <th>Created By</th>
            </thead>
            <tbody>
                @foreach ($data as $key => $row)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $row->prnum }}</td>
                    <td>{{ $row->prdate }}</td>
                    <td>{{ $row->no_plat }}</td>
                    <td>{{ $row->material }}</td>
                    <td>{{ $row->matdesc }}</td>
                    <td>{{ $row->deptname }}</td>
                    <td>{{ $row->createdby }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <a href="{{ url('approve/pr/detail') }}/{{ $pprid }}" target="_blank" >
            Approve / Reject PR
        </a>
    </div>
</body>
</html>