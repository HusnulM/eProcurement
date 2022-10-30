@extends('layouts/App')

@section('title', 'Master Kendaraan')

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
                        <button type="button" class="btn btn-success btn-sm btn-add-dept">
                            <i class="fas fa-plus"></i> Tambah Kendaraan
                        </button>
                        <!-- <a href="{{ url('/master/department/create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Create Department
                        </a> -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbl-dept-master" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>No</th>
                                <th>No Kendaraan</th>
                                <th>Type/Model Kendaraan</th>
                                <th>Engine S/N</th>
                                <th>Chassis S/N</th>
                                <th>Engine Model</th>
                                <th>Last KM</th>
                                <th>Last HM</th>
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
@endsection

@section('additional-modal')
<div class="modal fade" id="modal-add-department">
    <form action="{{ url('master/kendaraan/save') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tambah Kendaraan</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="nokend">No. Kendaraan</label>
                            <input type="text" name="nokend" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="model_kendaraan">Type/Model Kendaraan</label>
                            <input type="text" name="model_kendaraan" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="engine_sn">Engine S/N</label>
                            <input type="text" name="engine_sn" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="chassis_sn">Chassis S/N</label>
                            <input type="text" name="chassis_sn" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="engine_model">Engine Model</label>
                            <input type="text" name="engine_model" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="last_km">Last KM</label>
                            <input type="text" name="last_km" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="last_hm">Last HM</label>
                            <input type="text" name="last_hm" class="form-control" required>
                        </div>
                    </div> 
                </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </div>
        </div>
    </form>
</div>

<div class="modal fade" id="modal-edit-department">
    <form action="{{ url('master/kendaraan/update') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Data Kendaraan</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="nokend">No. Kendaraan</label>
                            <input type="text" name="nokend" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="model_kendaraan">Type/Model Kendaraan</label>
                            <input type="text" name="model_kendaraan" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="engine_sn">Engine S/N</label>
                            <input type="text" name="engine_sn" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="chassis_sn">Chassis S/N</label>
                            <input type="text" name="chassis_sn" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="engine_model">Engine Model</label>
                            <input type="text" name="engine_model" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="last_km">Last KM</label>
                            <input type="text" name="last_km" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="last_hm">Last HM</label>
                            <input type="text" name="last_hm" class="form-control" required>
                        </div>
                    </div> 
                </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </div>
        </div>
    </form>
</div>
@endsection

@section('additional-js')
<script>
    $(document).ready(function(){
        $("#tbl-dept-master").DataTable({
            serverSide: true,
            ajax: {
                url: base_url+'/master/kendaraan/kendaraanlist',
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
                {data: "no_kendaraan", className: 'uid'},
                {data: "model_kendaraan", className: 'uid'},
                {data: "engine_sn", className: 'uid'},
                {data: "chassis_sn", className: 'uid'},
                {data: "engine_model", className: 'uid'},
                {data: "last_km", className: 'uid'},
                {data: "last_hm", className: 'uid'},
                {"defaultContent": 
                    `<button class='btn btn-danger btn-sm button-delete'> <i class='fa fa-trash'></i> DELETE</button> 
                    <button class='btn btn-primary btn-sm button-edit'> <i class='fa fa-edit'></i> EDIT</button>
                    `,
                    "className": "text-center",
                    "width": "20%"
                }
            ]  
        });

        $('#tbl-dept-master tbody').on( 'click', '.button-delete', function () {
            var table = $('#tbl-dept-master').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            window.location = base_url+"/master/kendaraan/delete/"+selected_data.id;
        });
        $('#tbl-dept-master tbody').on( 'click', '.button-edit', function () {
            var table = $('#tbl-dept-master').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            // window.location = base_url+"/master/department/edit/"+selected_data.deptid;
            $('#whsname').val(selected_data.whsname);
            $('#whsid').val(selected_data.id);
            $('#modal-edit-department').modal('show');
        });

        $('.btn-add-dept').on('click', function(){
            $('#modal-add-department').modal('show');
        });

        $('.btn-add-new-dept').on('click', function(){
            $('#tbl-new-dept-body').append(`
                <tr>
                    <td>
                        <input type="text" name="nama[]" class="form-control"/>
                    </td>
                    <td style="text-align:center;">
                        <button type="button" class="btn btn-danger btn-sm btnRemove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);

            $('.btnRemove').on('click', function(e){
                e.preventDefault();
                $(this).closest("tr").remove();
            });
        });
    });
</script>
@endsection