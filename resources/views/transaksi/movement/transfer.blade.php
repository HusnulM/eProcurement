@extends('layouts/App')

@section('title', 'Transfer Barang')

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
    <form action="{{ url('logistic/transfer/save') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Transfer Barang</h3>
                        <div class="card-tools">
                            <button type="submit" class="btn btn-primary btn-sm btn-add-dept">
                                <i class="fas fa-save"></i> Post Transfer Barang
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2 col-md-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="grdate">Tanggal Transfer</label>
                                            <input type="date" name="grdate" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="recipient">Di Proses Oleh</label>
                                            <input type="text" name="recipient" class="form-control" value="{{ Auth::user()->name }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="remark">Remark</label>
                                            <textarea name="remark" id="remark" cols="30" rows="4" class="form-control" placeholder="Remark..."></textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="attachment">Attachment</label>
                                            <input type="file" class="form-control" name="efile[]" multiple="multiple">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table table-sm">
                                            <thead>
                                                <th>Part No. / Material</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Gudang Asal</th>
                                                <th>Gudang Tujuan</th>
                                                <th style="text-align:right;">
                                                    <button type="button" class="btn btn-success btn-sm btn-select-material">
                                                        <i class="fa fa-list"></i> List Material
                                                    </button>
                                                </th>
                                            </thead>
                                            <tbody id="tbl-pbj-body">

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
    </form>
</div>
@endsection

@section('additional-modal')
<div class="modal fade" id="modal-add-material">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Pilih Material</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <table id="tbl-material-list" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                        <thead>
                            <th>No</th>
                            <th>Part Number / Material</th>
                            <th>Description</th>
                            <th>Unit</th>
                            <th></th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>
@endsection

@section('additional-js')
<script src="{{ asset('/assets/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function(){
        var count = 0;

        let _token   = $('meta[name="csrf-token"]').attr('content');

        let selected_items    = [];

        var fCount = 0;

        $('.btn-select-material').on('click', function(){
            loadMaterial();
            $('#modal-add-material').modal('show');
        });

        function checkSelectedMaterial(pMaterial) {
            return selected_items.some(function(el) {
                if(el.material === pMaterial){
                    return true;
                }else{
                    return false;
                }
            });
        }

        function removeItem(index){
            selected_items.splice(index, 1);
        }

        function loadMaterial(){
            $("#tbl-material-list").DataTable({
                serverSide: true,
                ajax: {
                    url: base_url+'/allmaterial',
                    data: function (data) {
                        data.params = {
                            sac: "sac"
                        }
                    }
                },
                buttons: false,
                columns: [
                    { "data": null,"sortable": false, "searchable": false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {data: "material", className: 'uid'},
                    {data: "matdesc", className: 'fname'},
                    {data: "matunit", className: 'fname'},
                    {"defaultContent":
                        "<button type='button' class='btn btn-primary btn-sm button-add-material'> <i class='fa fa-plus'></i> Add</button>"
                    }
                ],
                "bDestroy": true,
            });

            $("#tbl-material-list tbody").on('click', '.button-add-material', function(){
                var menuTable = $('#tbl-material-list').DataTable();
                selected_data = [];
                selected_data = menuTable.row($(this).closest('tr')).data();

                if(checkSelectedMaterial(selected_data.material)){
                    console.log(selected_items);
                }else{
                    console.log(selected_data);
                    selected_items.push(selected_data);
                    fCount = fCount + 1;
                    $('#tbl-pbj-body').append(`
                        <tr>
                            <td>
                                `+selected_data.material+` - `+ selected_data.matdesc +`
                                <input type="hidden" name="parts[]" id="parts`+fCount+`" class="form-control" value="`+ selected_data.material +`" readonly>
                                <input type="hidden" name="partdesc[]" id="partdesc`+fCount+`" class="form-control" value="`+ selected_data.matdesc +`" readonly>
                            </td>

                            <td>
                                <input type="text" name="quantity[]" class="form-control inputNumber" style="text-align:right;" required>
                            </td>
                            <td>
                                `+ selected_data.matunit +`
                                <input type="hidden" name="uoms[]" id="partunit`+fCount+`" value="`+ selected_data.matunit +`" class="form-control" readonly>
                            </td>
                            <td>
                                <select name="whscode[]" id="find-whscode`+fCount+`" class="form-control" required></select>
                            </td>
                            <td>
                                <select name="whscodeto[]" id="find-whscode-to`+fCount+`" class="form-control" required></select>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger" id="btnRemove`+fCount+`">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `);

                    $('#btnRemove'+fCount).on('click', function(e){
                        e.preventDefault();
                        var row_index = $(this).closest("tr").index();
                        removeItem(row_index);
                        $(this).closest("tr").remove();
                    });

                    $('.inputNumber').on('change', function(){
                        this.value = formatRupiah(this.value,'');
                    });

                    $('.inputNumber').on('keypress', function(e){
                        validate(e);
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

                    $(document).on('select2:open', (event) => {
                        const searchField = document.querySelector(
                            `.select2-search__field`,
                        );
                        if (searchField) {
                            searchField.focus();
                        }
                    });
                    $('#find-whscode'+fCount).select2({
                        placeholder: 'Ketik Nama Gudang',
                        width: '100%',
                        minimumInputLength: 0,
                        ajax: {
                            url: base_url + '/master/warehouse/findwhs',
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
                                            text: item.whsname,
                                            slug: item.whsname,
                                            id: item.whscode,
                                            ...item
                                        }
                                    })
                                };
                            },
                            cache: true
                        }
                    });

                    $('#find-whscode-to'+fCount).select2({
                        placeholder: 'Ketik Nama Gudang',
                        width: '100%',
                        minimumInputLength: 0,
                        ajax: {
                            url: base_url + '/master/warehouse/findwhs',
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
                                            text: item.whsname,
                                            slug: item.whsname,
                                            id: item.whscode,
                                            ...item
                                        }
                                    })
                                };
                            },
                            cache: true
                        }
                    });
                }
            });
        }
    });
</script>
@endsection
