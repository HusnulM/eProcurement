@extends('layouts/App')

@section('title', 'Master Project')

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
                            <i class="fas fa-plus"></i> Tambah Project
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
                                <th>Project ID</th>
                                <th>Nama Project</th>
                                <th>Nilai Project</th>
                                <th>Project Manager</th>
                                <th>Manager Lapangan</th>
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
    <form action="{{ url('master/project/save') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tambah Project</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <label for="kodeProyek">Kode Proyek</label>
                        <input type="text" name="kodeProyek" class="form-control" required>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <label for="namaProyek">Nama Proyek</label>
                        <input type="text" name="namaProyek" class="form-control" required>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <label for="nilaiProyek">Nilai Proyek</label>
                        <input type="text" name="nilaiProyek" class="form-control" required>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <label for="projectManager">Project Manager</label>
                        <input type="text" name="projectManager" class="form-control" required>
                        {{-- <select name="projectManager" class="form-control" required>
                            <option value=""></option>
                            @foreach ($approver as $key => $row )
                            <option value="{{ $row->nama }}">{{ $row->nama }}</option>
                            @endforeach
                        </select> --}}
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <label for="managerLapangan">Manager Lapangan</label>
                        <input type="text" name="managerLapangan" class="form-control" required>
                        {{-- <select name="managerLapangan" class="form-control" required>
                            <option value=""></option>
                            @foreach ($approver as $key => $row )
                            <option value="{{ $row->nama }}">{{ $row->nama }}</option>
                            @endforeach
                        </select> --}}
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

<div class="modal fade" id="modal-edit-project">
    <form action="{{ url('master/project/update') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Data Project</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="idProject" id="idProject">
                    <div class="col-lg-12 col-md-12">
                        <label for="kodeProyek">Kode Proyek</label>
                        <input type="text" name="kodeProyek" id="kodeProyek" class="form-control" required>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <label for="namaProyek">Nama Proyek</label>
                        <input type="text" name="namaProyek" id="namaProyek" class="form-control" required>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <label for="nilaiProyek">Nilai Proyek</label>
                        <input type="text" name="nilaiProyek" id="nilaiProyek" class="form-control" required>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <label for="projectManager">Project Manager</label>
                        <input type="text" name="projectManager" id="projectManager" class="form-control" required>
                        {{-- <select name="projectManager" class="form-control" required>
                            <option value=""></option>
                            @foreach ($approver as $key => $row )
                            <option value="{{ $row->nama }}">{{ $row->nama }}</option>
                            @endforeach
                        </select> --}}
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <label for="managerLapangan">Manager Lapangan</label>
                        <input type="text" name="managerLapangan" id="managerLapangan" class="form-control" required>
                        {{-- <select name="managerLapangan" class="form-control" required>
                            <option value=""></option>
                            @foreach ($approver as $key => $row )
                            <option value="{{ $row->nama }}">{{ $row->nama }}</option>
                            @endforeach
                        </select> --}}
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
                url: base_url+'/master/project/projectlist',
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
                {data: "kode_project", className: 'uid'},
                {data: "nama_project", className: 'uid'},
                {data: "nilai_project", className: 'uid'},
                {data: "project_manager", className: 'uid'},
                {data: "manager_lapangan", className: 'uid'},
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
            window.location = base_url+"/master/project/delete/"+selected_data.id;
        });
        $('#tbl-dept-master tbody').on( 'click', '.button-edit', function () {
            var table = $('#tbl-dept-master').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            console.log(selected_data)
            // window.location = base_url+"/master/department/edit/"+selected_data.deptid;
            $('#kodeProyek').val(selected_data.kode_project);
            $('#namaProyek').val(selected_data.nama_project);
            $('#nilaiProyek').val(selected_data.nilai_project);
            $('#projectManager').val(selected_data.project_manager);
            $('#managerLapangan').val(selected_data.manager_lapangan);
            $('#idProject').val(selected_data.id);
            $('#modal-edit-project').modal('show');
        });

        $('.btn-add-dept').on('click', function(){
            $('#modal-add-department').modal('show');
        });

        $('.btn-add-new-dept').on('click', function(){
            $('#tbl-new-dept-body').append(`
                <tr>
                    <td>
                        <input type="text" name="kodeproject[]" class="form-control" required/>
                    </td>
                    <td>
                        <input type="text" name="namaproject[]" class="form-control" required/>
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
