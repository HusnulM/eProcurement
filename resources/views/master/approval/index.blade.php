@extends('layouts/App')

@section('title', 'Master Approval')

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
                            <i class="fas fa-plus"></i> Tambah Approval
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbl-dept-master" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
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
    <form action="{{ url('master/approval/save') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tambah Approval</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th style="width:50px; text-align:center;">
                                    <button type="button" class="btn btn-success btn-sm btn-add-new-dept">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </th>
                            </thead>
                            <tbody id="tbl-new-dept-body">

                            </tbody>
                        </table>
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
    <form action="{{ url('master/approval/update') }}" method="post">
        @csrf
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Data Approval</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>Nama</th>
                                <th>Jabatan</th>
                            </thead>
                            <tbody id="tbl-edit-dept-body">
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="nama" id="mknama" required>
                                        <input type="hidden" class="form-control" name="mkid" id="mkid">
                                    </td>
                                    <td>
                                        <select name="jabatan" id="mkjabatan" class="form-control" required>
                                            <option value=""></option>
                                            @foreach ($jabatan as $key => $row )
                                            <option value="{{ $row->jabatan }}">{{ $row->jabatan }}</option>
                                            @endforeach
                                        </select>
                                        {{-- <input type="text" class="form-control" name="jabatan" id="mkjabatan" required> --}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                url: base_url+'/master/approval/list',
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
                {data: "nama", className: 'uid'},
                {data: "jabatan", className: 'uid'},
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
            window.location = base_url+"/master/approval/delete/"+selected_data.id;
        });
        $('#tbl-dept-master tbody').on( 'click', '.button-edit', function () {
            var table = $('#tbl-dept-master').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            console.log(selected_data)
            // window.location = base_url+"/master/department/edit/"+selected_data.deptid;
            $('#mknama').val(selected_data.nama);
            $('#mkjabatan').val(selected_data.jabatan);
            $('#mkid').val(selected_data.id);
            $('#modal-edit-department').modal('show');
        });

        $('.btn-add-dept').on('click', function(){
            $('#modal-add-department').modal('show');
        });

        $('.btn-add-new-dept').on('click', function(){
            $('#tbl-new-dept-body').append(`
                <tr>
                    <td>
                        <input type="text" name="nama[]" class="form-control" required/>
                    </td>
                    <td>
                        <select name="jabatan[]" class="form-control" required>
                            <option value=""></option>
                            @foreach ($jabatan as $key => $row )
                                <option value="{{ $row->jabatan }}">{{ $row->jabatan }}</option>
                            @endforeach
                        </select>
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
