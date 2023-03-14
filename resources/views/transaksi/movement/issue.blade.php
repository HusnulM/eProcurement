@extends('layouts/App')

@section('title', 'Pengeluaran Barang')

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
    <form action="{{ url('logistic/pengeluaran/save') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pengeluaran Barang</h3>
                        <div class="card-tools">
                            <button type="submit" class="btn btn-primary btn-sm btn-add-dept">
                                <i class="fas fa-save"></i> Simpan Pengeluaran Barang
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2 col-md-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="grdate">Tanggal Pengeluaran</label>
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
                                                <th>Part No. / Type</th>
                                                <!-- <th>Description</th> -->
                                                <th>Quantity / Unit</th>
                                                <th>Gudang</th>
                                                <!-- <th>Unit</th> -->
                                                <!-- <th>Unit Price</th> -->
                                                <th>PBJ Reference</th>
                                                <th style="text-align:right;">
                                                    <button type="button" class="btn btn-success btn-sm btn-add-po-item-based-pr">
                                                        <i class="fa fa-list"></i> List PBJ
                                                    </button>
                                                </th>
                                            </thead>
                                            <tbody id="tbl-pbj-body">

                                            </tbody>
                                            <!-- <tfoot>
                                                <tr>
                                                    <td colspan="7"></td>
                                                    <td style="text-align:right;">
                                                        <button type="button" class="btn btn-success btn-sm btn-add-pbj-item">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tfoot> -->
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
<div class="modal fade" id="modal-list-pr">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pilih PO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table id="tbl-pbj-list" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th></th>
                                <th>Nomor PBJ</th>
                                <th>Tanggal PBJ</th>
                                <th>Part Number</th>
                                <th>Part Name</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Figure</th>
                                <th>Remark</th>
                                <th style="width:50px; text-align:center;">
                                    
                                </th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>   
                    </div> 
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <!-- <button type="submit" class="btn btn-primary">Save</button> -->
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

        let selected_pbj_items = [];

        $('.btn-add-po-item-based-pr').on('click', function(){
            loadListPBJ();
            $('#modal-list-pr').modal('show');
        });    
        
        function checkPbjSelected(pbjNum, pbjItem) {
            return selected_pbj_items.some(function(el) {
                if(el.pbjnumber === pbjNum && el.pbjitem === pbjItem){
                    return true;
                }else{
                    return false;
                }
            }); 
        }

        function removePbjItem(index){
            selected_pbj_items.splice(index, 1);
        }            

        var fCount = 0;
        function loadListPBJ(){
            $("#tbl-pbj-list").DataTable({
                serverSide: true,
                ajax: {
                    url: base_url+'/proc/pr/listapprovedpbj',
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
                    {data: "pbjnumber", className: 'uid'},
                    {data: "tgl_pbj", className: 'uid'},
                    {data: "partnumber"},
                    {data: "description"},
                    {data: "quantity", "className": "text-right",},
                    {data: "unit"},      
                    {data: "figure"},
                    {data: "remark"},      
                    {"defaultContent": 
                        `
                        <button class='btn btn-success btn-sm button-add-pbj-to-pritem'> <i class="fa fa-plus"></i></button>
                        `,
                        "className": "text-center",
                        "width": "10%"
                    }
                ] ,
                bDestroy: true,
            });

            $('#tbl-pbj-list tbody').on( 'click', '.button-add-pbj-to-pritem', function () {
                var table = $('#tbl-pbj-list').DataTable();
                selected_data = [];
                selected_data = table.row($(this).closest('tr')).data();

                if(checkPbjSelected(selected_data.pbjnumber, selected_data.pbjitem)){
                    console.log(selected_pbj_items);
                }else{
                    selected_pbj_items.push(selected_data);
                    console.log(selected_pbj_items);
                
                    console.log(selected_data);
                    fCount = fCount + 1;
                    $('#tbl-pbj-body').append(`
                        <tr>
                            <td>
                                <select name="parts[]" class="form-control" readonly>
                                    <option value="`+selected_data.partnumber+`">`+selected_data.partnumber+`</option>
                                </select>
                                <input type="text" name="partdesc[]" id="partdesc`+fCount+`" class="form-control" value="`+selected_data.description+`" readonly>
                            </td>
                            
                            <td>
                                <input type="text" name="quantity[]" class="form-control inputNumber" value="`+selected_data.quantity+`" required>
                                <input type="text" name="uoms[]" id="partunit`+fCount+`" class="form-control" value="`+selected_data.unit+`" readonly>
                            </td>
                            <td>
                                <select name="whscode[]" id="find-whscode`+fCount+`" class="form-control"></select>
                            </td>
                            <td>
                                <input type="text" name="pbjref[]" id="pbjref`+fCount+`" class="form-control" value="`+selected_data.pbjnumber+`">
                                <input type="hidden" name="pbjnum[]" id="pbjnum`+fCount+`" class="form-control" value="`+selected_data.pbjnumber+`">
                                <input type="hidden" name="pbjitm[]" id="pbjitm`+fCount+`" class="form-control" value="`+selected_data.pbjitem+`">
                            </td>
                            <td style="text-align:center;">
                                <button type="button" class="btn btn-danger btnRemove">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `);
        
                    $('.btnRemove').on('click', function(e){
                        e.preventDefault();
                        var row_index = $(this).closest("tr").index();
                        removePbjItem(row_index);
                        $(this).closest("tr").remove();
                    });
    
                    $('.inputNumber').on('change', function(){
                        this.value = formatRupiah(this.value,'');
                    });
    
                    $('.inputNumber').on('keypress', function(e){
                        validate(e);
                    });
    
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
                }
            });

        }

    });
</script>
@endsection