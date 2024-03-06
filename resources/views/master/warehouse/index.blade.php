@extends('layouts/App')

@section('title', 'Master Gudang')

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
                            <i class="fas fa-plus"></i> Tambah Gudang
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
                                <th>Kode Gudang</th>
                                <th>Nama Gudang</th>
                                <th>Alamat</th>
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
    <form action="{{ url('master/warehouse/save') }}" method="post">
        @csrf
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tambah Gudang</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label for="kodeGudang">Kode Gudang</label>
                        <input type="text" name="kodeGudang" class="form-control" required>
                    </div>
                    <div class="col-lg-12">
                        <label for="namaGudang">Nama Gudang</label>
                        <input type="text" name="namaGudang" class="form-control" required>
                    </div>
                    <div class="col-lg-12">
                        <label for="alamatGudang">Alamat</label>
                        <textarea name="alamatGudang" cols="10" rows="5" class="form-control" required></textarea>
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
    <form action="{{ url('master/warehouse/update') }}" method="post">
        @csrf
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Data Gudang</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label for="kodeGudang">Kode Gudang</label>
                        <input type="text" name="kodeGudang" id="kodeGudang" class="form-control" readonly required>
                        <input type="hidden" class="form-control" name="whsid" id="whsid">
                    </div>
                    <div class="col-lg-12">
                        <label for="namaGudang">Nama Gudang</label>
                        <input type="text" class="form-control" name="whsname" id="whsname">
                    </div>
                    <div class="col-lg-12">
                        <label for="alamatGudang">Alamat</label>
                        <textarea name="alamatGudang" id="alamatGudang" cols="10" rows="5" class="form-control" required></textarea>
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
                url: base_url+'/master/warehouse/warehouselist',
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
                {data: "whscode", className: 'uid'},
                {data: "whsname", className: 'uid'},
                {data: "address", className: 'uid'},
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
            window.location = base_url+"/master/warehouse/delete/"+selected_data.id;
        });
        $('#tbl-dept-master tbody').on( 'click', '.button-edit', function () {
            var table = $('#tbl-dept-master').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            // window.location = base_url+"/master/department/edit/"+selected_data.deptid;
            $('#kodeGudang').val(selected_data.whscode);
            $('#whsname').val(selected_data.whsname);
            $('#alamatGudang').val(selected_data.address);
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
                        <input type="text" name="kodegudang[]" class="form-control"/>
                    </td>
                    <td>
                        <input type="text" name="namagudang[]" class="form-control"/>
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
