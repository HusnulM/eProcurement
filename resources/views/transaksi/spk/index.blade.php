@extends('layouts/App')

@section('title', 'Create Work Order')

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
    <form action="{{ url('logistic/wo/save') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create Work Order</h3>
                        <div class="card-tools">
                            <button type="submit" class="btn btn-primary btn-sm btn-add-dept">
                                <i class="fas fa-save"></i> Simpan Work Order
                            </button>
                            <a href="{{ url('/logistic/wo/listwo') }}" class="btn btn-success btn-sm">
                                <i class="fa fa-list"></i> List Work Order
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
                                            <input type="text" name="descr" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-12">
                                        <div class="form-group">
                                            <label for="servicedate">Service Date</label>
                                            <input type="date" name="servicedate" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="mekanik">Mekanik</label>
                                            <select name="mekanik" id="find-mekanik" class="form-control" required></select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="currency">Warehouse</label>                                            
                                            <select name="whscode" id="find-whscode" class="form-control" required></select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="licenseNumber">License Plate Number</label>
                                            <!-- <input type="text" name="licenseNumber" class="form-control" required> -->
                                            <select name="licenseNumber" id="find-licenseNumber" class="form-control" required></select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="lastOdoMeter">Last Odo Meter</label>                                            
                                            <input type="text" name="lastOdoMeter" id="lastOdoMeter" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="lastKM">Last KM</label>                                            
                                            <input type="text" name="lastKM" id="lastKM" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="lastHM">Last HM</label>                                            
                                            <input type="text" name="lastHM" id="lastHM" class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="jenis_kendaraan">Jenis Kendaraan</label>                                            
                                            <input type="text" name="jenis_kendaraan" id="jenis_kendaraan" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="bahan_bakar">Bahan Bakar</label>                                            
                                            <input type="text" name="bahan_bakar" id="bahan_bakar" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="tahun">Tahun</label>                                            
                                            <input type="text" name="tahun" id="tahun" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="nomor_rangka">Nomor Rangka</label>                                            
                                            <input type="text" name="nomor_rangka" id="nomor_rangka" class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-12">
                                        <div class="form-group">
                                            <label for="schedule">Status Schedule</label>                                            
                                            <select name="schedule" id="schedule" class="form-control" required>
                                                <option value="">Pilih Status Schedule</option>
                                                <option value="Schedule">Schedule</option>
                                                <option value="Un-Schedule">Un-Schedule</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="issued">Issued</label>
                                            <input type="text" name="issued" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-12">
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
                                                {{-- <th>Description</th> --}}
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th style="text-align:right;">
                                                    <button type="button" class="btn btn-success btn-sm btn-add-pbj-item">
                                                        <i class="fa fa-plus"></i>
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
                            <th>Part Number</th>
                            <th>Warehouse</th>
                            <th>Warehouse Name</th>
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
                                <th>Unit</th>
                                <th>Request By</th>
                                <th>Department</th>
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
                <button type="submit" class="btn btn-primary">Save</button>
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
        let selected_items = [];
        // $('.btn-add-po-item-based-pr').on('click', function(){
        //     loadListPR();
        //     $('#modal-list-pr').modal('show');
        // });        

        var fCount = 0;
        function loadMaterial(){
            $whsID = $('#find-whscode').val();
            // alert($whsID)
            if($whsID == null){
                toastr.error('Pilih Warehouse');
            }else{
                $('#modal-add-material').modal('show');
                $("#tbl-material-list").DataTable({
                    serverSide: true,
                    ajax: {
                        url: base_url+'/matstockbywhs/'+$whsID,
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
                        {data: "partnumber", className: 'fname'},
                        {data: "whsnum", className: 'fname'},
                        {data: "whsname", className: 'fname'},
                        {data: "quantity", className: 'fname'},
                        {data: "unit", className: 'fname'},
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
                                    <input type="text" name="quantity[]" class="form-control inputNumber" onkeypress="`+validate(event)+`" required>
                                </td>
                                <td>
                                    <input type="text" name="uoms[]" id="partunit`+fCount+`" value="`+ selected_data.unit +`" readonly class="form-control">
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
                
                function formatNumber(num) {
                    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
                }

                $('.inputNumber').on('change', function(){
                    this.value = formatNumber(this.value);
                });
            }
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
        });

        // $('.btn-add-pbj-item').on('click', function(){
        //     fCount = fCount + 1;
        //     $('#tbl-pbj-body').append(`
        //         <tr>
        //             <td>
        //                 <select name="parts[]" id="find-part`+fCount+`" class="form-control"></select>
        //             </td>
        //             <td>
        //                 <input type="text" name="partdesc[]" id="partdesc`+fCount+`" class="form-control">
        //             </td>
        //             <td>
        //                 <input type="text" name="quantity[]" class="form-control inputNumber" onkeypress="`+validate(event)+`">
        //             </td>
        //             <td>
        //                 <input type="text" name="uoms[]" id="partunit`+fCount+`" class="form-control">
        //             </td>
        //             <td>
        //                 <button type="button" class="btn btn-danger btnRemove">
        //                     <i class="fa fa-trash"></i>
        //                 </button>
        //             </td>
        //         </tr>
        //     `);

        //     $('.btnRemove').on('click', function(e){
        //         e.preventDefault();
        //         $(this).closest("tr").remove();
        //     });

        //     $(document).on('select2:open', (event) => {
        //         const searchField = document.querySelector(
        //             `.select2-search__field`,
        //         );
        //         if (searchField) {
        //             searchField.focus();
        //         }
        //     });

        //     $('#find-part'+fCount).select2({ 
        //         placeholder: 'Type Part Number',
        //         width: '100%',
        //         minimumInputLength: 0,
        //         ajax: {
        //             url: base_url + '/master/item/findpartnumber',
        //             dataType: 'json',
        //             delay: 250,
        //             method: 'POST',
        //             headers: {
        //                 'X-CSRF-TOKEN': _token
        //             },
        //             data: function (params) {
        //                 var query = {
        //                     search: params.term,
        //                     // custname: $('#find-customer').val()
        //                 }
        //                 return query;
        //             },
        //             processResults: function (data) {
        //                 // return {
        //                 //     results: response
        //                 // };
        //                 console.log(data)
        //                 return {
        //                     results: $.map(data.data, function (item) {
        //                         return {
        //                             text: item.material + ' - ' + item.matdesc,
        //                             slug: item.material,
        //                             id: item.material,
        //                             ...item
        //                         }
        //                     })
        //                 };
        //             },
        //             cache: true
        //         }
        //     });

        //     $('#find-part'+fCount).on('change', function(){
        //         // alert(this.value)
        //         console.log(fCount)
        //         var data = $('#find-part'+fCount).select2('data')
        //         console.log(data);

        //         // alert(data[0].material);
        //         $('#partdesc'+fCount).val(data[0].partname);
        //         $('#partunit'+fCount).val(data[0].matunit);
        //     });

        //     function validate(evt) {
        //         var theEvent = evt || window.event;

        //         // Handle paste
        //         if (theEvent.type === 'paste') {
        //             key = event.clipboardData.getData('text/plain');
        //         } else {
        //         // Handle key press
        //             var key = theEvent.keyCode || theEvent.which;
        //             key = String.fromCharCode(key);
        //         }
        //         var regex = /[0-9]|\./;
        //         if( !regex.test(key) ) {
        //             theEvent.returnValue = false;
        //             if(theEvent.preventDefault) theEvent.preventDefault();
        //         }
        //     }
            
        //     function formatNumber(num) {
        //         return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        //     }

        //     $('.inputNumber').on('change', function(){
        //         this.value = formatRupiah(this.value,'');
        //     });

        //     function formatRupiah(angka, prefix){
        //         var number_string = angka.toString().replace(/[^.\d]/g, '').toString(),
        //         split   		  = number_string.split('.'),
        //         sisa     		  = split[0].length % 3,
        //         rupiah     		  = split[0].substr(0, sisa),
        //         ribuan     		  = split[0].substr(sisa).match(/\d{3}/gi);
            
        //         if(ribuan){
        //             separator = sisa ? ',' : '';
        //             rupiah += separator + ribuan.join(',');
        //         }
            
        //         rupiah = split[1] != undefined ? rupiah + '.' + split[1] : rupiah;
        //         return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');            
        //     }
        // });

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

        $('#find-mekanik').select2({ 
            placeholder: 'Ketik Nama Mekanik',
            width: '100%',
            minimumInputLength: 0,
            ajax: {
                url: base_url + '/master/mekanik/findmekanik',
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
                                text: item.nama,
                                slug: item.nama,
                                id: item.nama,
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
                url: base_url + '/master/kendaraan/findkendaraan',
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

        $('#find-licenseNumber').on('change', function(){
            // alert(this.value)
            
            var data = $('#find-licenseNumber').select2('data')
            console.log(data);
            $('#lastKM').val(data[0].last_km);
            $('#lastHM').val(data[0].last_hm);

            $('#jenis_kendaraan').val(data[0].model_kendaraan);
            $('#nomor_rangka').val(data[0].no_rangka);
            $('#bahan_bakar').val(data[0].bahan_bakar);
            $('#tahun').val(data[0].tahun);
        });
        
    });
</script>
@endsection