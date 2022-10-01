@extends('layouts/App')

@section('title', 'Item Master')

@section('additional-css')
@endsection

@section('content')        
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">Item Master</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-content-above-itemtype-tab" data-toggle="pill" href="#custom-content-above-itemtype" role="tab" aria-controls="custom-content-above-itemtype" aria-selected="false">Item Type</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-content-above-itemunit-tab" data-toggle="pill" href="#custom-content-above-itemunit" role="tab" aria-controls="custom-content-above-itemunit" aria-selected="false">Item Unit</a>
                </li>
            </ul>
            <div class="tab-content" id="custom-content-above-tabContent">
                <div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"></h3>
                            <div class="card-tools">
                                <a href="{{ url('/master/item/create') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Create Item Master
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tbl-item-master" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                    <thead>
                                        <th>No</th>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Item Type</th>
                                        <th>Item Unit</th>
                                        <th style="text-align:center;"></th>
                                    </thead>
                                    <tbody>
            
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="custom-content-above-itemtype" role="tabpanel" aria-labelledby="custom-content-above-itemtype-tab">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"></h3>
                            <div class="card-tools">
                                <a href="{{ url('/master/item/create') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Create Item Type
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tbl-item-type" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                    <thead>
                                        <th>No</th>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Item Type</th>
                                        <th>Item Unit</th>
                                        <th style="text-align:center;"></th>
                                    </thead>
                                    <tbody>
            
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="custom-content-above-itemunit" role="tabpanel" aria-labelledby="custom-content-above-itemunit-tab">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"></h3>
                            <div class="card-tools">
                                <a href="{{ url('/master/item/create') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Create Item Unit
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tbl-item-unit" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                    <thead>
                                        <th>No</th>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Item Type</th>
                                        <th>Item Unit</th>
                                        <th style="text-align:center;"></th>
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

@section('additional-js')
<script>
    $(document).ready(function(){
        $("#tbl-item-master").DataTable({
            serverSide: true,
            ajax: {
                url: base_url+'/master/item/itemlist',
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
                {data: "material", className: 'uid'},
                {data: "matdesc", className: 'fname'},
                {data: "mattype", className: 'uname'},
                {data: "matunit", className: 'uname'},
                {"defaultContent": 
                    `<button class='btn btn-danger btn-sm button-delete'> <i class='fa fa-trash'></i> DELETE</button> 
                    <button class='btn btn-primary btn-sm button-edit'> <i class='fa fa-edit'></i> EDIT</button>
                    `
                }
            ]  
        });

        $('#tbl-item-master tbody').on( 'click', '.button-delete', function () {
            var table = $('#tbl-item-master').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            window.location = base_url+"/master/item/delete/"+selected_data.materialid;
        });
        $('#tbl-item-master tbody').on( 'click', '.button-edit', function () {
            var table = $('#tbl-item-master').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            window.location = base_url+"/master/item/edit/"+selected_data.materialid;
        });
    });
</script>
@endsection