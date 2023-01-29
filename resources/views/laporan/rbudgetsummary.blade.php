@extends('layouts/App')

@section('title', 'Laporan Pengajuan Budget')

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
                        
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="tbl-budget-list" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                <thead>
                                    <th>No</th>
                                    <th>Department</th>
                                    <th>Saldo Budget</th>
                                    <th>Issuing Budget</th>
                                    <th>Sisa Budget</th>
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
            var param = '?datefrom='+ $('#datefrom').val() +'&dateto='+ $('#dateto').val()+'&department='+$('#department').val()+'&approvalstat='+$('#approvalStatus').val();
            loadDocument(param);
        });

        loadDocument('');

        function loadDocument(_params){
            $("#tbl-budget-list").DataTable({
                serverSide: true,
                ajax: {
                    url: base_url+'/report/budgetsummarylist'+_params,
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
                    {data: "deptname", className: 'uid'},
                    {data: "amount", "sortable": false,
                        render: function (data, type, row){
                            return ``+ row.amount.amount1 + ``;
                        },
                        "className": "text-right",
                    },
                    {data: "issuing_amount",  "sortable": false,
                        render: function (data, type, row){
                            return ``+ row.issuing_amount.amount2 + ``;
                        },
                        "className": "text-right",
                    },
                    {data: "balance",  "sortable": false,
                        render: function (data, type, row){
                            return ``+ row.balance.amount3 + ``;
                        },
                        "className": "text-right",
                    }
                ]  
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