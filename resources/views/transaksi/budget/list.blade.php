@extends('layouts/App')

@section('title', 'List Pengajuan Budget')

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
                        <a href="{{ url('transaction/budgeting') }}" class="btn btn-success btn-sm btn-add-dept">
                            <i class="fas fa-plus"></i> Buat Pengajuan Budget
                        </a>
                        <!-- <a href="{{ url('/master/department/create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Create Department
                        </a> -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbl-budget-list" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>No</th>
                                <th>Nomor PTA</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Nominal di Ajukan</th>
                                <th>Nominal di Setujui</th>
                                <th>Status</th>
                                <th>Remark</th>
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
@endsection

@section('additional-modal')

@endsection

@section('additional-js')
<script>
    $(document).ready(function(){
        $("#tbl-budget-list").DataTable({
            serverSide: true,
            ajax: {
                url: base_url+'/transaction/budgeting/budgetlist',
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
            columns: [
                { "data": null,"sortable": false, "searchable": false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }  
                },
                {data: "ptanumber", className: 'uid'},
                {data: "tgl_aju", className: 'uid'},
                {data: "amount", "sortable": false,
                    render: function (data, type, row){
                        return ``+ row.amount.amount1 + ``;
                    },
                    "className": "text-right",
                },
                {data: "approved_amount",  "sortable": false,
                    render: function (data, type, row){
                        return ``+ row.approved_amount.amount2 + ``;
                    },
                    "className": "text-right",
                },
                {data: "budget_status", 
                    render: function (data, type, row){
                        if(row.budget_status == "O"){
                            return `Open`;
                        }else if(row.budget_status == "A"){
                            return `Approved`;
                        }
                    }
                },
                {data: "remark" }
            ]  
        });
    });
</script>
@endsection