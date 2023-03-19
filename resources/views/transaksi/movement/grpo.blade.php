@extends('layouts/App')

@section('title', 'Receipt Purchase Order')

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
    <form action="{{ url('logistic/terimapo/save') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Receipt Purchase Order</h3>
                        <div class="card-tools">
                            <button type="submit" class="btn btn-primary btn-sm btn-add-dept">
                                <i class="fas fa-save"></i> Simpan Receipt Purchase Order
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2 col-md-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="grdate">Tanggal Terima PO</label>
                                            <input type="date" name="grdate" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="recipient">Di terima oleh</label>
                                            <input type="text" name="recipient" class="form-control" value="{{ Auth::user()->name }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="remark">Remark</label>
                                            <textarea name="remark" id="remark" cols="30" rows="4" class="form-control" placeholder="Remark..."></textarea>
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
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Gudang</th>
                                                <th>Unit</th>
                                                <th>Unit Price</th>
                                                <th>PO Reference</th>
                                                <th style="text-align:right;">
                                                    <button type="button" class="btn btn-success btn-sm btn-add-po-item-based-pr">
                                                        <i class="fa fa-list"></i> List PO
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
                        <table id="tbl-pr-list" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th></th>
                                <th>Nomor PO</th>
                                <th>Tanggal PO</th>
                                <th>Part Number</th>
                                <th>Part Name</th>
                                <th>Quantity</th>
                                <th>Open Qty</th>
                                <th>Unit</th>
                                <th>No PR</th>
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
        let selected_po_items = [];
        let _token   = $('meta[name="csrf-token"]').attr('content');

        $('.btn-add-po-item-based-pr').on('click', function(){
            loadListPO();
            $('#modal-list-pr').modal('show');
        });        

        var fCount = 0;
        function loadListPO(){
            $("#tbl-pr-list").DataTable({
                serverSide: true,
                ajax: {
                    url: base_url+'/logistic/terimapo/listapprovedpo',
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
                    {data: "ponum", className: 'uid'},
                    {data: "podat", className: 'uid'},
                    {data: "material"},
                    {data: "matdesc"},
                    {data: "quantity", "className": "text-right"},
                    {data: "openqty", "className": "text-right"},
                    {data: "unit"},      
                    {data: "prnum"},  
                    {data: "note"},      
                    {"defaultContent": 
                        `
                        <button class='btn btn-success btn-sm button-add-pbj-to-pritem'> <i class="fa fa-plus"></i></button>
                        `,
                        "className": "text-center",
                        "width": "10%"
                    }
                ]  
            });

            

            function checkPOSelected(poNum, poItem) {
                return selected_po_items.some(function(el) {
                    if(el.ponum === poNum && el.poitem === poItem){
                        return true;
                    }else{
                        return false;
                    }
                }); 
            }

            function removePoItem(index){
                selected_po_items.splice(index, 1);
            }

            $('#tbl-pr-list tbody').on( 'click', '.button-add-pbj-to-pritem', function () {
                var table = $('#tbl-pr-list').DataTable();
                selected_data = [];
                selected_data = table.row($(this).closest('tr')).data();
                if(checkPOSelected(selected_data.ponum, selected_data.poitem)){
                    console.log(selected_po_items);
                }else{
                    selected_po_items.push(selected_data);
                    fCount = fCount + 1;
                    $('#tbl-pbj-body').append(`
                        <tr>
                            <td>
                            <input type="text" name="parts[]" id="parts`+fCount+`" value="`+ selected_data.material +`" class="form-control" readonly>
                            </td>
                            <td>
                                <input type="text" name="partdesc[]" id="partdesc`+fCount+`" value="`+ selected_data.matdesc +`" class="form-control" readonly>
                            </td>
                            <td>
                                <input type="text" name="quantity[]" class="form-control inputNumber" value="`+ selected_data.openqty +`" id="grqty`+fCount+`" data-openqty="`+ selected_data.openqty +`">
                            </td>
                            <td>
                                <input type="text" name="uoms[]" id="partunit`+fCount+`" value="`+ selected_data.unit +`" class="form-control" readonly>
                            </td>
                            <td>
                            <select name="whscode[]" id="find-whscode`+fCount+`" class="form-control" required></select>
                            </td>
                            <td>
                                <input type="text" name="unitprice[]" class="form-control inputNumber" value="`+ selected_data.price +`" readonly>
                            </td>
                            <td>
                                <input type="text" name="poref[]" id="prref`+fCount+`" value="`+ selected_data.ponum +`" class="form-control">
                                <input type="hidden" name="ponum[]" id="prnum`+fCount+`" value="`+ selected_data.ponum +`" class="form-control">
                                <input type="hidden" name="poitem[]" id="pritem`+fCount+`" value="`+ selected_data.poitem +`" class="form-control">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btnRemove" id="btnRemove`+fCount+`">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `);

                    $('#grqty'+fCount).on('change', function(){
                        var _data = $(this).data();
                        let openQty = _data.openqty;
                        let inptQty = this.value;
                        // alert(inptQty)
                        inptQty = inptQty*1;
                        openQty = openQty*1;
                        if(inptQty > openQty){
                            alert('Deficit Quantity');
                            this.value = openQty;
                        }
                        console.log(_data)
                    });
    
                    $('#btnRemove'+fCount).on('click', function(e){
                        e.preventDefault();
                        var row_index = $(this).closest("tr").index();
                        removePoItem(row_index);
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