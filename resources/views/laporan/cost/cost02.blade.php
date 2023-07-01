@extends('layouts/App')

@section('title', 'Laporan Cost Per Project')

@section('additional-css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style type="text/css">
        .select2-container {
            display: block
        }

        .select2-container .select2-selection--single {
            height: 36px;
        }
    </style>
@endsection

@section('content')        
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Cost Per Project</h3>
                    <div class="card-tools">
                        <!-- <a href="{{ url('transaction/budgeting') }}" class="btn btn-success btn-sm btn-add-dept">
                            <i class="fas fa-plus"></i> Buat Pengajuan Budget
                        </a> -->
                        <!-- <a href="{{ url('/master/department/create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Create Department
                        </a> -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="{{ url('report/exportcost02') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <label for="">Dari Tanggal</label>
                                        <input type="date" class="form-control" name="datefrom" id="datefrom" value="{{ $_GET['datefrom'] ?? '' }}">
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <label for="">-</label>
                                        <input type="date" class="form-control" name="dateto" id="dateto" value="{{ $_GET['dateto'] ?? '' }}">
                                    </div>
                                    
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <label for="">Project</label>
                                        <select name="project" id="project" class="form-control">
                                            <option value="All">All</option>
                                            @foreach($project as $key => $row)
                                                <option value="{{ $row->kode_project }}">{{ $row->nama_project }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="licenseNumber">No. Plat Kendaraan</label>
                                            <select name="licenseNumber" id="find-licenseNumber" class="form-control"></select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-6 col-sm-12" style="text-align:center;">
                                        <br>
                                        <button type="button" class="btn btn-default mt-2 btn-search"> 
                                            <i class="fa fa-search"></i> Filter
                                        </button>
                                        <button type="button" class="btn btn-default mt-2 btn-reset-filter"> 
                                            <i class="fa fa-refresh"></i> Reset Filter
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
                        <div class="table-responsive">
                            <table id="tbl-budget-list" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                <thead>
                                    <th>No</th>
                                    <th>Project</th>
                                    <th>No. Plat</th>
                                    <th>Model Kendaraan</th>
                                    <th>Tanggal</th>
                                    <th>Total Cost</th>
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
@endsection

@section('additional-modal')

@endsection

@section('additional-js')
<script src="{{ asset('/assets/js/select2.min.js') }}"></script>
<script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>
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

        let _token   = $('meta[name="csrf-token"]').attr('content');

        $('.btn-search').on('click', function(){
            var nopol = $('#find-licenseNumber').val();
            // alert(nopol)
            if(nopol == null){
                // alert('ok')kodeproj
                var param = '?datefrom='+ $('#datefrom').val() +'&dateto='+ $('#dateto').val()+'&kodeproj='+ $('#project').val();
            }else{
                // alert('tes')
                var param = '?datefrom='+ $('#datefrom').val() +'&dateto='+ $('#dateto').val()+'&nopol='+$('#find-licenseNumber').val()+'&kodeproj='+ $('#project').val();
            }
            // alert(param)
            loadDocument(param);
        });

        $('.btn-reset-filter').on('click', function(){
            $('#datefrom').val('');
            $('#dateto').val('');
            $('#project').val('');
            // $('#find-licenseNumber').val('');
            $("#find-licenseNumber").select2("val", "");
            loadDocument('');
        });

        loadDocument('');

        function loadDocument(_params){
            $("#tbl-budget-list").DataTable({
                serverSide: true,
                ajax: {
                    url: base_url+'/report/cost02list'+_params,
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
                    {data: "nama_project", className: 'uid'},
                    {data: "no_plat", className: 'uid'},
                    {data: "type_model", className: 'uid'},                    
                    {data: "postdate", className: 'uid',
                        render: function (data, type, row){
                            return ``+ row.postdate.postdate1 + ``;
                        }
                    },          
                    {data: "total_cost", "sortable": false, 
                        render: function (data, type, row){
                            return ``+ row.total_cost.totalprice + ``;
                        },
                        "className": "text-right",
                    }
                ],
                order: [[1, 'asc']],
                rowGroup: {
                    startRender: null,
                    endRender: function ( rows, group ) {
                        var data  = [];
                        var rdata = [];
                        rdata = rows.data()
                        data  = rows.data()[rdata.length-1];

                        console.log(rdata.length);
                        var totalPrice = 0;
                        for(var i = 0; i < rdata.length; i++){
                            console.log(rdata[i].total_cost.totalprice2)

                            totalPrice = totalPrice*1 + rdata[i].total_cost.totalprice2*1;
                        }

                        return $('<tr>')
                            .append( '<td colspan="5" align="right"><b>Total Cost</b></td>' )
                            .append( '<td style="text-align:right;"><b>'+ formatRupiah(totalPrice,'') +'</b></td>' )
                            
                            .append( '</tr>' );
                    },
                    dataSrc: 1
                }
                // rowGroup: {
                //     dataSrc: 1
                // }
            });
        }
        

        $('.inputNumber').on('change', function(){
            this.value = formatRupiah(this.value,'');
        });

        $(document).on('select2:open', (event) => {
            const searchField = document.querySelector(
                `.select2-search__field`,
            );
            if (searchField) {
                searchField.focus();
            }
        });

        $('#find-licenseNumber').select2({ 
            placeholder: 'No Kendaraan',
            width: '100%',
            minimumInputLength: 0,
            allowClear: true,
            ajax: {
                url: base_url + '/logistic/wo/findkendaraan',
                dataType: 'json',
                delay: 250,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': _token
                },
                data: function (params) {
                    var query = {
                        search: params.term,
                        // custname: $('#find-customer').val()
                    }
                    return query;
                },
                processResults: function (data) {
                    // return {
                    //     results: response
                    // };
                    console.log(data)
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.no_kendaraan + ' - ' + item.model_kendaraan,
                                slug: item.model_kendaraan,
                                id: item.no_kendaraan,
                                ...item
                            }
                        })
                    };
                },
                cache: true
            }
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