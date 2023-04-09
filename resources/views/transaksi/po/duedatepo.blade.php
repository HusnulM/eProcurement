@extends('layouts/App')

@section('title', 'List Due Date PO')

@section('additional-css')
@endsection

@section('content')        
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List Due Date PO</h3>
                    <div class="card-tools">
                        
                        {{-- <a href="{{ url('/proc/po') }}" class='btn btn-default btn-sm'> 
                            <i class='fa fa-arrow-left'></i> Back
                        </a> --}}
                    </div>
                </div>
                <div class="card-body">
                    
                    <div class="row">
                        <div class="table-responsive">
                            <table id="tbl-budget-list" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                <thead>
                                    <th>No</th>
                                    <th>Nomor PO</th>
                                    <th>Tanggal PO</th>
                                    <th>Material</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Receipt Qty</th>
                                    <th>Open Qty</th>
                                    <th>Unit</th>
                                    <th>Aging Days</th>
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

        loadDocument('');

        function loadDocument(_params){
            $("#tbl-budget-list").DataTable({
                serverSide: true,
                ajax: {
                    url: base_url+'/proc/po/duedatepolist',
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
                    {data: "ponum", className: 'uid',
                        // render: function (data, type, row){
                        //     return `<span style="color:red;">`+ row.ponum + `</span>`;
                        //     // $('td', row).eq(0).css('color', 'red');
                        // },
                    },
                    {data: "podat", className: 'uid',
                        render: function (data, type, row){
                            return ``+ row.podat.podat1 + ``;
                        }
                    },
                    {data: "material", className: 'uid'},
                    {data: "matdesc", className: 'uid'},
                    {data: "quantity", "sortable": false,
                        render: function (data, type, row){
                            return ``+ row.quantity.qty1 + ``;
                        },
                        "className": "text-right",
                    },
                    {data: "grqty", "sortable": false,
                        render: function (data, type, row){
                            return ``+ row.grqty.qty2 + ``;
                        },
                        "className": "text-right",
                    },
                    {data: "openqty", "sortable": false,
                        render: function (data, type, row){
                            return ``+ row.openqty.qty3 + ``;
                        },
                        "className": "text-right",
                    },
                    {data: "unit"},                               
                    {data: "duedate" },
                ]  
            });

            $('#tbl-budget-list tbody').on( 'click', '.button-print', function () {                
                var table = $('#tbl-budget-list').DataTable();
                selected_data = [];
                selected_data = table.row($(this).closest('tr')).data();
                // window.location = base_url+"/proc/pr/print";
                // if(selected_data.doctype === "Corporate Procedure"){
                    window.open(
                        base_url+"/proc/po/print/"+selected_data.id,
                        '_blank' // <- This is what makes it open in a new window.
                    );
                // }
            });

            $('#tbl-budget-list tbody').on( 'click', '.button-detail', function () {                
                var table = $('#tbl-budget-list').DataTable();
                selected_data = [];
                selected_data = table.row($(this).closest('tr')).data();
                window.location = "/proc/po/detail/"+selected_data.id;
            });

            $('#tbl-budget-list tbody').on( 'click', '.button-change', function () {                
                var table = $('#tbl-budget-list').DataTable();
                selected_data = [];
                selected_data = table.row($(this).closest('tr')).data();
                window.location = "/proc/po/change/"+selected_data.id;
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