@extends('layouts/App')

@section('title', 'Pembuatan Purchase Order')

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
    <form action="{{ url('proc/po/save') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Purchase Order</h3>
                        <div class="card-tools">
                            <button type="submit" class="btn btn-primary btn-sm btn-add-dept">
                                <i class="fas fa-save"></i> Simpan Purchase Order
                            </button>
                            <a href="{{ url('/proc/po/listpo') }}" class="btn btn-success btn-sm">
                                <i class="fa fa-list"></i> List PO
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2 col-md-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="tglreq">Tanggal PO</label>
                                            <input type="date" name="tglreq" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="deldate">Delivery Date</label>
                                            <input type="date" name="deldate" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="vendor">Vendor</label>
                                            <select name="vendor" id="find-vendor" class="form-control"></select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="kepada">Department</label>
                                            <select name="department" id="department" class="form-control" required>
                                                <option value="">Pilih Department</option>
                                                @foreach($department as $key => $row)
                                                    <option value="{{ $row->deptid }}">{{ $row->department }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="requestor">Creator</label>
                                            <input type="text" name="requestor" class="form-control" value="{{ Auth::user()->name }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="currency">Currency</label>                                            
                                            <select name="currency" id="currency" class="form-control">
                                                <option value="IDR">IDR - Indonesian Rupiah</option>
                                                <option value="USD">USD - US Dollar</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="department">Department</label>
                                            <input type="text" name="department" class="form-control" value="{{ getUserDepartment() }}" readonly>
                                        </div>
                                    </div> -->
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
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">PO Items</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-content-above-cost-tab" data-toggle="pill" href="#custom-content-above-cost" role="tab" aria-controls="custom-content-above-cost" aria-selected="false">Additional Cost</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-tools">
                                            <a href="{{ url('/approve/pbj') }}" class="btn btn-default btn-sm">
                                                <i class="fa fa-arrow-left"></i> Back
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="tab-content" id="custom-content-above-tabContent">
                                                    <div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
                                                        <div class="row">
                                                            
                                                            <div class="col-lg-12">
                                                                <table id="tbl-po-item" class="table table-sm">
                                                                    <thead>
                                                                        <th>Part Number</th>
                                                                        {{-- <th>Description</th> --}}
                                                                        <th>Quantity</th>
                                                                        <th>Unit</th>
                                                                        <th>Unit Price</th>
                                                                        <th>PR Reference</th>
                                                                        <th style="text-align:right;">
                                                                            <button type="button" class="btn btn-success btn-sm btn-add-pbj-item">
                                                                                <i class="fa fa-plus"></i>
                                                                            </button>
                                                                            <button type="button" class="btn btn-success btn-sm btn-add-po-item-based-pr">
                                                                                <i class="fa fa-list"></i> List PR
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

                                                    <div class="tab-pane fade" id="custom-content-above-cost" role="tabpanel" aria-labelledby="custom-content-above-cost-tab">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <label for="top">Term of Payment</label>
                                                                <input type="text" class="form-control" name="termofpayment">
                                                            </div>
                                                            <hr>
                                                            <div class="col-lg-4 col-md-12 mb-2 mt-2">
                                                                <label for="PPn">PPN</label>
                                                                <select name="ppn" id="ppn" class="form-control form-sm">
                                                                    <option value="">---</option>
                                                                    <option value="11">11 %</option>
                                                                </select>
                                                            </div>
                                                            {{-- <hr> --}}
                                                            <div class="col-lg-8">
                                                                <table class="table table-sm">
                                                                    <thead>
                                                                        <th>Cost Component</th>
                                                                        <th>Cost Amount</th>
                                                                        <th style="text-align: right;">
                                                                            <button type="button" class="btn btn-success btn-sm" id="btn-add-cost">
                                                                                <i class="fa fa-add"></i>
                                                                            </button>
                                                                        </th>
                                                                    </thead>
                                                                    <tbody id="tbl-cost-body">

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
                <h4 class="modal-title">Pilih PR</h4>
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
                                <th>Nomor PR</th>
                                <th>Tanggal PR</th>
                                <th>Part Number</th>
                                <th>Part Name</th>
                                <th>Quantity</th>
                                <th>Quantity PO</th>
                                <th>Open Quantity</th>
                                <th>Unit</th>
                                <th>Request By</th>
                                <th>Department</th>
                                <th>No. Plat</th>
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
                            {{-- <th>Part Number</th> --}}
                            {{-- <th>Warehouse</th>
                            <th>Warehouse Name</th>
                            <th>Available Quantity</th> --}}
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
        let selected_pr_items = [];
        let selected_items    = [];
        let _token   = $('meta[name="csrf-token"]').attr('content');

        $('.btn-add-po-item-based-pr').on('click', function(){
            loadListPR();
            $('#modal-list-pr').modal('show');
        });     
        
        $('#btn-add-cost').on('click', function(){
            count = count + 1;
            $('#tbl-cost-body').append(`
                <tr>
                    <td>
                        <input type="text" name="costname[]" class="form-control" required>    
                    </td>
                    <td>
                        <input type="text" name="costvalue[]" class="form-control" required>    
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" id="btnRemoveCost`+count+`">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);

            $('#btnRemoveCost'+count).on('click', function(e){
                e.preventDefault();
                $(this).closest("tr").remove();
            });
        });

        var fCount = 0;
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
                    // {data: "partnumber", className: 'fname'},
                    // {data: "whsnum", className: 'fname'},
                    // {data: "whsname", className: 'fname'},
                    // {data: "quantity", className: 'fname'},
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
                                <input type="text" name="quantity[]" class="form-control inputNumber" required>
                            </td>
                            <td>
                                <input type="text" name="uoms[]" id="partunit`+fCount+`" value="`+ selected_data.matunit +`" class="form-control" readonly>
                            </td>
                            <td>
                                <input type="text" name="unitprice[]" class="form-control inputNumber" value="`+ selected_data.last_purchase_price +`">
                            </td>
                            <td>
                                <input type="text" name="prref[]" id="prref`+fCount+`" class="form-control">
                                <input type="hidden" name="prnum[]" id="prnum`+fCount+`" value="" class="form-control">
                                <input type="hidden" name="pritem[]" id="pritem`+fCount+`" value="" class="form-control">
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
                }
            });
        }

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

        $('.btn-add-pbj-item').on('click', function(){
            loadMaterial();
            $('#modal-add-material').modal('show');
        });

        $(document).on('select2:open', (event) => {
            const searchField = document.querySelector(
                `.select2-search__field`,
            );
            if (searchField) {
                searchField.focus();
            }
        });
        $('#find-vendor').select2({ 
            placeholder: 'Type Vendor Name',
            width: '100%',
            minimumInputLength: 0,
            ajax: {
                url: base_url + '/master/vendor/findvendor',
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
                                text: item.vendor_name,
                                slug: item.vendor_name,
                                id: item.vendor_code,
                                ...item
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $('#find-vendor').on('change', function(){
            // alert(this.value)
            
            var data = $('#find-vendor').select2('data')
            console.log(data);

            // alert(data[0].material);
            // $('#partdesc'+fCount).val(data[0].partname);
            // $('#partunit'+fCount).val(data[0].matunit);
        });


        function loadListPR(){
            $("#tbl-pr-list").DataTable({
                serverSide: true,
                ajax: {
                    url: base_url+'/proc/po/listapprovedpr',
                    data: function (data) {
                        data.params = {
                            sac: "sac",
                            deptid: $('#department').val()
                        }
                    }
                },
                buttons: false,
                searching: true,
                // scrollY: 500,
                // scrollX: true,
                bDestroy: true,
                scrollCollapse: true,
                columns: [
                    { "data": null,"sortable": false, "searchable": false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }  
                    },
                    {data: "prnum", className: 'uid'},
                    {data: "prdate", className: 'uid'},
                    {data: "material"},
                    {data: "matdesc"},
                    {data: "quantity", "className": "text-right"},
                    {data: "poqty", "className": "text-right"},
                    {data: "openqty", "className": "text-right"},
                    {data: "unit"},      
                    {data: "requestby"},
                    {data: "department"},      
                    {data: "no_plat"},      
                    {data: "remark"},      
                    {"defaultContent": 
                        `
                        <button class='btn btn-success btn-sm button-add-pbj-to-pritem'> <i class="fa fa-plus"></i></button>
                        `,
                        "className": "text-center",
                        "width": "10%"
                    }
                ]  
            });

            

            function checkPRSelected(prNum, prItem) {
                return selected_pr_items.some(function(el) {
                    if(el.prnum === prNum && el.pritem === prItem){
                        return true;
                    }else{
                        return false;
                    }
                }); 
            }

            function removePrItem(index){
                selected_pr_items.splice(index, 1);
            }

            $('#tbl-pr-list tbody').on( 'click', '.button-add-pbj-to-pritem', function () {
                var table = $('#tbl-pr-list').DataTable();
                selected_data = [];
                selected_data = table.row($(this).closest('tr')).data();

                if(checkPRSelected(selected_data.prnum, selected_data.pritem)){
                    console.log(selected_pr_items);
                }else{
                    selected_pr_items.push(selected_data);
                    fCount = fCount + 1;
                    $('#tbl-pbj-body').append(`
                        <tr>
                            <td>
                                `+selected_data.material+` - `+ selected_data.matdesc +`
                                <input type="hidden" name="parts[]" id="parts`+fCount+`" class="form-control" value="`+ selected_data.material +`" readonly>
                                <input type="hidden" name="partdesc[]" id="partdesc`+fCount+`" class="form-control" value="`+ selected_data.matdesc +`" readonly>
                            </td>
                            <td>
                                <input type="text" name="quantity[]" class="form-control inputNumber" id="inputQty`+fCount+`" value="`+ selected_data.openqty +`" data-openqty="`+ selected_data.openqty +`">
                            </td>
                            <td>
                                <input type="text" name="uoms[]" id="partunit`+fCount+`" value="`+ selected_data.unit +`" class="form-control" readonly>
                            </td>
                            <td>
                                <input type="text" name="unitprice[]" class="form-control inputNumber" value="`+ selected_data.last_purchase_price +`">
                            </td>
                            <td>
                                <input type="text" name="prref[]" id="prref`+fCount+`" value="`+ selected_data.prnum +`" class="form-control">
                                <input type="hidden" name="prnum[]" id="prnum`+fCount+`" value="`+ selected_data.prnum +`" class="form-control">
                                <input type="hidden" name="pritem[]" id="pritem`+fCount+`" value="`+ selected_data.pritem +`" class="form-control">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btnRemove" id="btnRemove`+fCount+`">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `);
    
                    checkTabledata();
                    
                    $('#inputQty'+fCount).on('change', function(){
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
                        removePrItem(row_index);
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
                }
            });

        }

        function checkTabledata(){
            var oTable = document.getElementById('tbl-po-item');

            //gets rows of table
            var rowLength = oTable.rows.length;

            //loops through rows    
            for (i = 0; i < rowLength; i++){
                //gets cells of current row
                var oCells = oTable.rows.item(i).cells;
                console.log(oCells)
                //gets amount of cells of current row
                var cellLength = oCells.length;

                //loops through each cell in current row
                for(var j = 0; j < cellLength; j++){
                    /* get your cell info here */
                    /* var cellVal = oCells.item(j).innerHTML; */
                    console.log(oCells.item(j))
                }
            }
        }
    });
</script>
@endsection