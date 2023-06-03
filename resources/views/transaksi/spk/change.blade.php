@extends('layouts/App')

@section('title', 'Update Work Order')

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
    {{-- action="{{ url('logistic/wo/update') }}" --}}
    <form id="form-submit-data" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Update Work Order <b>[ {{ $prhdr->wonum }} ]</b></h3>
                        <div class="card-tools">
                            <a href="{{ url('/logistic/wo/listwo') }}" class="btn btn-default btn-sm">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm btn-add-dept">
                                <i class="fas fa-save"></i> Update Work Order
                            </button>
                            <a href="{{ url('/logistic/wo/delete') }}/{{ $prhdr->id }}" class="btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i> Delete Work Order
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="row">
                                    <div class="col-lg-9 col-md-12">
                                        <div class="form-group">
                                            <label for="descr">Description</label>
                                            <input type="text" name="descr" class="form-control" value="{{ $prhdr->description }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-12">
                                        <div class="form-group">
                                            <label for="servicedate">Service Date</label>
                                            <input type="date" name="servicedate" class="form-control" value="{{ $prhdr->wodate }}" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="currency">Warehouse</label>                                            
                                            <select name="whscode" id="find-whscode" class="form-control" required>
                                                <option value="{{ $prhdr->whscode }}">{{ $prhdr->whsname }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="licenseNumber">No. Plat Kendaraan</label>
                                            <!-- <input type="text" name="licenseNumber" class="form-control" required> -->
                                            <select name="licenseNumber" id="find-licenseNumber" class="form-control" required>
                                                <option value="{{ $prhdr->license_number }}">{{ $prhdr->license_number }} - {{ $kendaraan->model_kendaraan }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-12">
                                        <div class="form-group">
                                            <label for="schedule">Status Schedule</label>                                            
                                            <select name="schedule" id="schedule" class="form-control" required>
                                                <option value="{{ $prhdr->schedule_type }}">{{ $prhdr->schedule_type }}</option>
                                                <option value="Schedule">Schedule</option>
                                                <option value="Un-Schedule">Un-Schedule</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="issued">Issued</label>
                                            <input type="text" name="issued" class="form-control" value="{{ $prhdr->issued }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="attachment">Attachment</label>
                                            <input type="file" class="form-control" name="efile[]" multiple="multiple">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table table-sm">
                                            <thead>
                                                <th>Part Number / Material</th>
                                                
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>PBJ Number</th>
                                                <th style="text-align:right;">
                                                    <button type="button" class="btn btn-success btn-sm btn-select-pbj-item">
                                                        <i class="fa fa-list"></i> List Material
                                                    </button>
                                                </th>
                                            </thead>
                                            <tbody id="tbl-pbj-body">
                                                @foreach($pritem as $key => $row)
                                                <tr>
                                                    <td>
                                                        {{ $row->material }} - {{ $row->matdesc }}
                                                        <input type="hidden" name="parts[]" class="form-control" value="{{ $row->material }}">
                                                        <input type="hidden" name="partdesc[]" class="form-control" value="{{ $row->matdesc }}">
                                                        <input type="hidden" name="woitem[]" value="{{ $row->woitem }}" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="quantity[]" class="form-control inputNumber" value="{{ number_format($row->quantity) }}" style="text-align:right;" required>
                                                    </td>
                                                    <td>
                                                        {{ $row->unit }}
                                                        <input type="hidden" name="uoms[]" value="{{ $row->unit }}" readonly class="form-control">
                                                    </td>
                                                    <td>
                                                        {{ $row->refdoc }}
                                                        <input type="hidden" name="pbjnum[]" class="form-control" value="{{ $row->refdoc }}" readonly>
                                                        {{-- <input type="hidden" name="pbjitm[]" class="form-control" value="{{ $row->refdocitem }}">         --}}
                                                    </td>
                                                    <td style="text-align:center;">
                                                        <button type="button" class="btn btn-danger btn-sm btn-delete-woitem" data-wonum="{{ $row->wonum }}" data-woitem="{{ $row->woitem }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
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
                            <th>Material</th>
                            <th>Description</th>
                            {{-- <th>Part Number</th>
                            <th>Warehouse</th>
                            <th>Warehouse Name</th> --}}
                            <th>Available Quantity</th>
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

<div class="modal fade" id="modal-list-pbj">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pilih PBJ</h4>
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
                                <th>No. Plat</th>
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
        let selected_items     = [];
        let selected_pbj_items = [];     

        var fCount = 0;

        
        
        $('.btn-delete-woitem').on('click', function(){
            var _adata = $(this).data();
            console.log(_adata)
            // let _token   = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: base_url+'/logistic/wo/deleteitem',
                type:"POST",
                data:{
                    wonum: _adata.wonum,
                    woitem: _adata.woitem,
                    _token: _token
                },
                beforeSend:function(){
                    $('.btn-delete-item').attr('disabled','disabled');
                    // showBasicMessage();
                },
                success:function(response){
                    console.log(response);
                    if(response.msgtype === "200"){
                        // if(_action === "A"){
                        toastr.success(response.message)
                        // }else if(_action === "R"){
                        //     toastr.success(response.message)
                        // }                        
                        // $(this).closest("tr").remove();
                        setTimeout(function(){ 
                            window.location.href = base_url+'/logistic/wo/change/{{ $prhdr->id }}';
                        }, 2000);
                    }else{
                        toastr.error(response.message);
                        setTimeout(function(){ 
                            location.reload();
                        }, 2000);
                    }
                },
                error: function(error) {
                    console.log(error);
                    toastr.error(error)

                    setTimeout(function(){ 
                        location.reload();
                    }, 2000);
                }
            }).done(function(response){
                console.log(response);
                // $(this).closest("tr").remove();
            });
        });

        $(document).on('select2:open', (event) => {
            const searchField = document.querySelector(
                `.select2-search__field`,
            );
            if (searchField) {
                searchField.focus();
            }
        });
        $('#find-whscode').select2({ 
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

        $('#find-licenseNumber').select2({ 
            placeholder: 'No Kendaraan',
            width: '100%',
            minimumInputLength: 0,
            ajax: {
                url: base_url + '/logistic/wo/findkendaraan',
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
                                text: item.no_kendaraan + ' - ' + item.model_kendaraan,
                                slug: item.model_kendaraan,
                                id: item.no_kendaraan,
                                ...item
                            }
                        })
                    };
                },
                cache: true
            }
        });
        
        $('.btn-select-pbj-item').on('click', function(){
            loadMaterial();            
        });

        function loadMaterial(){
            $('#modal-add-material').modal('show');
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
                    {data: "availableQty", "className": "text-right"},
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
                    selected_items.push(selected_data);
                        console.log(selected_pbj_items);
                        fCount = fCount + 1;
                        $('#tbl-pbj-body').append(`
                            <tr>
                                <td>
                                    `+selected_data.material+` - `+ selected_data.matdesc +`
                                    <input type="hidden" name="parts[]" id="parts`+fCount+`" class="form-control" value="`+ selected_data.material +`" readonly>
                                    <input type="hidden" name="partdesc[]" id="partdesc`+fCount+`" class="form-control" value="`+ selected_data.matdesc +`" readonly>
                                    <input type="hidden" name="woitem[]" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="quantity[]" style="text-align:right;" class="form-control inputNumber" onkeypress="`+validate(event)+`" required>
                                </td>
                                <td>
                                    `+ selected_data.matunit +`
                                    <input type="hidden" name="uoms[]" id="partunit`+fCount+`" value="`+ selected_data.matunit +`" readonly class="form-control">
                                    </td>
                                <td>`+"{{ $cklist->no_checklist }}"+`
                                    <input type="hidden" name="pbjnum[]" id="pbjnum`+fCount+`" class="form-control" value="{{ $cklist->no_checklist }}" readonly>
                                    <input type="hidden" name="pbjitm[]" id="pbjitm`+fCount+`" class="form-control" value="">        
                                </td>
                                <td style="text-align:center;">
                                    <button type="button" class="btn btn-danger btn-sm" id="btnRemove`+fCount+`">
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

                            console.log(selected_pbj_items);
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

        function loadListPBJ(){
            let noPol = $('#find-licenseNumber').val();
            // alert(noPol)
            if(noPol == null){
                toastr.error("Pilih Kendaraan");
            }else{
                $('#modal-list-pbj').modal('show');
                $("#tbl-pbj-list").DataTable({
                    serverSide: true,
                    ajax: {
                        url: base_url+'/logistic/wo/listapprovedpbj',
                        data: function (data) {
                            data.params = {
                                sac: "sac",
                                nopol: noPol
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
                        {data: "unit_desc"},      
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
    
                // function checkPbjSelected(pbjNum, pbjItem) {
                //     return selected_pbj_items.some(function(el) {
                //         if(el.pbjnumber === pbjNum && el.pbjitem === pbjItem){
                //             return true;
                //         }else{
                //             return false;
                //         }
                //     }); 
                // }
    
                // function removePbjItem(index){
                //     selected_pbj_items.splice(index, 1);
                // }
    
                $('#tbl-pbj-list tbody').on( 'click', '.button-add-pbj-to-pritem', function () {
                    var table = $('#tbl-pbj-list').DataTable();
                    selected_data = [];
                    selected_data = table.row($(this).closest('tr')).data();
                    
    
                    if(checkPbjSelected(selected_data.pbjnumber, selected_data.pbjitem)){
                        console.log(selected_pbj_items);
                    }else{
                        selected_pbj_items.push(selected_data);
                        console.log(selected_pbj_items);
                        fCount = fCount + 1;
                        var selQty = selected_data.quantity;
                        selQty = selQty.replace('.000','');
                        $('#tbl-pbj-body').append(`
                            <tr>
                                <td>
                                    `+selected_data.partnumber+` - `+ selected_data.description +`
                                    <input type="hidden" name="parts[]" id="parts`+fCount+`" class="form-control" value="`+ selected_data.partnumber +`" readonly>
                                    <input type="hidden" name="partdesc[]" id="partdesc`+fCount+`" class="form-control" value="`+ selected_data.description +`" readonly>
                                </td>
                                <td>
                                    <input type="text" name="quantity[]" class="form-control inputNumber" value="`+ selQty +`" style="text-align:right;" onkeypress="`+validate(event)+`" required>
                                </td>
                                <td>
                                    `+ selected_data.unit +`
                                    <input type="hidden" name="uoms[]" id="partunit`+fCount+`" value="`+ selected_data.unit +`" readonly class="form-control">
                                    </td>
                                <td>
                                    `+selected_data.pbjnumber+`
                                    <input type="hidden" name="pbjnum[]" id="pbjnum`+fCount+`" class="form-control" value="`+selected_data.pbjnumber+`" readonly>
                                    <input type="hidden" name="pbjitm[]" id="pbjitm`+fCount+`" class="form-control" value="`+selected_data.pbjitem+`">     
                                    <input type="hidden" name="woitem[]" class="form-control">   
                                </td>
                                <td style="text-align:center;">
                                    <button type="button" class="btn btn-sm btn-danger" id="btnRemove`+fCount+`">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `);                    
            
                        $('#btnRemove'+fCount).on('click', function(e){
                            e.preventDefault();
                            var row_index = $(this).closest("tr").index();
                            removePbjItem(row_index);
                            $(this).closest("tr").remove();
    
                            console.log(selected_pbj_items);
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

        }

        $('#form-submit-data').on('submit', function(event){
            event.preventDefault();
            // action="{{ url('proc/pr/update') }}/{{ $prhdr->id }}" 
            var formData = new FormData(this);
            console.log($(this).serialize())
            $.ajax({
                url:base_url+'/logistic/wo/update/{{ $prhdr->id }}',
                method:'post',
                data:formData,
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend:function(){
                    $('.btn-update-pr').attr('disabled','disabled');
                    // showBasicMessage();
                },
                success:function(data)
                {

                },
                error:function(error){
                    toastr.error(error)
                    setTimeout(function(){ 
                        location.reload();
                    }, 2000);
                }
            }).done(function(result){
                console.log(result)
                if(result.msgtype === "200"){
                    toastr.success(result.message)
                    setTimeout(function(){ 
                        window.location.href = base_url+'/logistic/wo/change/{{ $prhdr->id }}';
                    }, 2000);
                }else{
                    toastr.error(result.message)
                    setTimeout(function(){ 
                        location.reload();
                    }, 2000);
                }
            }) ;
            
        });
    });
</script>
@endsection