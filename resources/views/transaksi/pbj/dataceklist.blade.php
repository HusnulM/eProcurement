@extends('layouts/App')

@section('title', 'Data Cek List Kendaraan')

@section('additional-css')
@endsection

@section('content')        
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    <div class="card-tools">
                        <!-- <a href="{{ url('transaction/budgeting') }}" class="btn btn-success btn-sm btn-add-dept">
                            <i class="fas fa-plus"></i> Buat Pengajuan Budget
                        </a> -->
                        <!-- <a href="{{ url('/master/department/create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Create Department
                        </a> -->
                        <!-- <a href="{{ url('/transaction/pbj') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back
                        </a> -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="{{ url('datachecklistkendaraan/export') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-2">
                                        <label for="">Tanggal Ceklist</label>
                                        <input type="date" class="form-control" name="datefrom" id="datefrom" value="{{ $_GET['datefrom'] ?? '' }}">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="">-</label>
                                        <input type="date" class="form-control" name="dateto" id="dateto" value="{{ $_GET['dateto'] ?? '' }}">
                                    </div>
                                    <div class="col-lg-3" style="text-align:center;">
                                        <br>
                                        <button type="button" class="btn btn-default mt-2 btn-search"> 
                                            <i class="fa fa-search"></i> Filter
                                        </button>
                                        <button type="submit" class="btn btn-success mt-2 btn-export"> 
                                            <i class="fa fa-download"></i> Export Data
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table id="tbl-data-ceklist" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                    <thead>
                                        <th>No</th>
                                        <th>Nomor Cek List</th>
                                        <th>Tanggal Cek List</th>
                                        <th>No Plat</th>
                                        <th>Jenis Kendaraan</th>
                                        <th>Nomor Rangka</th>
                                        <th>Bahan Bakar</th>
                                        <th>Tahun</th>
                                        <th>Odometer</th>
                                        <th>Nama Driver</th>
                                        <th>Hasil Pemeriksaan</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
            
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional-modal')

@endsection

@section('additional-js')
<script>
    function validate(evt) {
        var theEvent = evt || window.event;

        // Handle paste
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
        // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }
        var regex = /[0-9]|\./;
        if( !regex.test(key) ) {
            theEvent.returnValue = false;
            if(theEvent.preventDefault) theEvent.preventDefault();
        }
    }

    $(document).ready(function(){
        $('.btn-search').on('click', function(){
            var param = '?datefrom='+ $('#datefrom').val() +'&dateto='+ $('#dateto').val();
            loadCeklist(param);
        });

        loadCeklist('');
        function loadCeklist(_params){
            $("#tbl-data-ceklist").DataTable({
                serverSide: true,
                ajax: {
                    url: base_url+'/datachecklistkendaraan/datachecklist'+_params,
                    data: function (data) {
                        data.params = {
                            sac: "sac"
                        }
                    }
                },
                buttons: false,
                searching: true,
                scrollY: 500,
                scrollX: true,
                scrollCollapse: true,
                bDestroy: true,
                columns: [
                    { "data": null,"sortable": false, "searchable": false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }  
                    },
                    {data: "no_checklist", className: 'uid'},
                    {data: "tanggal_cek", className: 'uid'},
                    {data: "no_kendaraan"},
                    {data: "jenis_kendaraan"},             
                    {data: "nomor_rangka" },
                    {data: "bahan_bakar"},
                    {data: "tahun"},
                    {data: "odometer"},
                    {data: "nama_driver"},
                    {data: "hasil_pemeriksaan"},
                    {"defaultContent": 
                        `
                        <button class='btn btn-primary btn-sm button-detail'> <i class='fa fa-search'></i> Detail</button>
                        `,
                        "className": "text-center",
                        "width": "10%"
                    }
                ]  
            });
    
            $('#tbl-data-ceklist tbody').on( 'click', '.button-detail', function () {
                var table = $('#tbl-data-ceklist').DataTable();
                selected_data = [];
                selected_data = table.row($(this).closest('tr')).data();
                console.log(selected_data)
                window.location = base_url+"/datachecklistkendaraan/detail/"+selected_data.id;
            });
        }

        // $('#tbl-pbj-list tbody').on( 'click', '.button-print', function () {    
        //     var table = $('#tbl-pbj-list').DataTable();
        //     selected_data = [];
        //     selected_data = table.row($(this).closest('tr')).data();
        //         window.open(
        //             base_url+"/printdoc/pbj/print/"+selected_data.id,
        //             '_blank'
        //         );
        // });
                        

        $('.inputNumber').on('change', function(){
            this.value = formatRupiah(this.value,'');
        });

        function formatRupiah(angka, prefix){
            var number_string = angka.toString().replace(/[^.\d]/g, '').toString(),
            split   		  = number_string.split('.'),
            sisa     		  = split[0].length % 3,
            rupiah     		  = split[0].substr(0, sisa),
            ribuan     		  = split[0].substr(sisa).match(/\d{3}/gi);
        
            if(ribuan){
                separator = sisa ? ',' : '';
                rupiah += separator + ribuan.join(',');
            }
        
            rupiah = split[1] != undefined ? rupiah + '.' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');            
        }
    });
</script>
@endsection