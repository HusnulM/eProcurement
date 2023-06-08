@extends('layouts/App')

@section('title', 'List BAST')

@section('additional-css')
@endsection

@section('content')        
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List BAST</h3>
                    <div class="card-tools">
                        <a href="{{ url('/logistic/bast') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="{{ url('report/exportpr') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-2">
                                        <label for="">Tanggal BAST</label>
                                        <input type="date" class="form-control" name="datefrom" id="datefrom" value="{{ $_GET['datefrom'] ?? '' }}">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="">-</label>
                                        <input type="date" class="form-control" name="dateto" id="dateto" value="{{ $_GET['dateto'] ?? '' }}">
                                    </div>
                                    
                                    <div class="col-lg-4" style="text-align:right;">
                                        <br>
                                        <button type="button" class="btn btn-default mt-2 btn-search"> 
                                            <i class="fa fa-search"></i> Filter
                                        </button>
                                        {{-- <button type="submit" class="btn btn-success mt-2 btn-export"> 
                                            <i class="fa fa-download"></i> Export Data
                                        </button> --}}
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="tbl-bast-list" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                <thead>
                                    <th>No</th>
                                    <th>Nomor BAST</th>
                                    <th>Tanggal BAST</th>
                                    <th>Pemberi</th>
                                    <th>Penerima</th>
                                    <th>Remark</th>
                                    <th>Created Date</th>
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
            loadDocument(param);
        });

        loadDocument('');

        function loadDocument(_params){
            $("#tbl-bast-list").DataTable({
                serverSide: true,
                ajax: {
                    url: base_url+'/logistic/bast/listdatabast'+_params,
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
                    {data: "no_bast", className: 'uid'},
                    {data: "tanggal_bast", className: 'uid'},
                    {data: "pemberi", className: 'uid'},
                    {data: "penerima", className: 'uid'},
                    {data: "remark", className: 'uid'},
                    {data: "createdon", className: 'uid'},
                    {"defaultContent": 
                        `<button class='btn btn-primary btn-sm button-print'> <i class='fa fa-search'></i> View </button>
                        `,
                        "className": "text-center",
                    }
                ]  
            });

            $('#tbl-bast-list tbody').on( 'click', '.button-print', function () {                
                var table = $('#tbl-bast-list').DataTable();
                selected_data = [];
                selected_data = table.row($(this).closest('tr')).data();
                window.location = base_url+"/logistic/bast/detail/"+selected_data.id;
                    // window.open(
                    //     base_url+"/proc/pr/print/"+selected_data.id,
                    //     '_blank' // <- This is what makes it open in a new window.
                    // );
            });
        }
        

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