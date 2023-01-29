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
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table id="tbl-po-item" class="table table-sm">
                                            <thead>
                                                <th>Part No. / Type</th>
                                                <th>Description</th>
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

        $('.btn-add-po-item-based-pr').on('click', function(){
            loadListPR();
            $('#modal-list-pr').modal('show');
        });        

        var fCount = 0;

        $('.btn-add-pbj-item').on('click', function(){
            fCount = fCount + 1;
            $('#tbl-pbj-body').append(`
                <tr>
                    <td>
                        <select name="parts[]" id="find-part`+fCount+`" class="form-control"></select>
                    </td>
                    <td>
                        <input type="text" name="partdesc[]" id="partdesc`+fCount+`" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="quantity[]" class="form-control inputNumber" onkeypress="`+validate(event)+`">
                    </td>
                    <td>
                        <input type="text" name="uoms[]" id="partunit`+fCount+`" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="unitprice[]" class="form-control inputNumber" onkeypress="`+validate(event)+`">
                    </td>
                    <td>
                        <input type="text" name="prref[]" id="prref`+fCount+`" class="form-control">
                        <input type="hidden" name="prnum[]" id="prnum`+fCount+`" value="" class="form-control">
                        <input type="hidden" name="pritem[]" id="pritem`+fCount+`" value="" class="form-control">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btnRemove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);

            

            $('.btnRemove').on('click', function(e){
                e.preventDefault();
                $(this).closest("tr").remove();
            });

            $(document).on('select2:open', (event) => {
                const searchField = document.querySelector(
                    `.select2-search__field`,
                );
                if (searchField) {
                    searchField.focus();
                }
            });

            $('#find-part'+fCount).select2({ 
                placeholder: 'Type Part Number',
                width: '100%',
                minimumInputLength: 0,
                ajax: {
                    url: base_url + '/master/item/findpartnumber',
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
                                    text: item.partnumber,
                                    slug: item.partnumber,
                                    id: item.partnumber,
                                    ...item
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $('#find-part'+fCount).on('change', function(){
                // alert(this.value)
                
                var data = $('#find-part'+fCount).select2('data')
                console.log(data);

                // alert(data[0].material);
                $('#partdesc'+fCount).val(data[0].partname);
                $('#partunit'+fCount).val(data[0].matunit);
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
                this.value = formatRupiah(this.value,'');
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
                    {data: "quantity", "className": "text-right",},
                    {data: "poqty", "className": "text-right",},
                    {data: "openqty", "className": "text-right",},
                    {data: "unit"},      
                    {data: "requestby"},
                    {data: "department"},      
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

            let selected_pr_items = [];

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
                                <select name="parts[]" id="find-part`+fCount+`" class="form-control" readonly>
                                    <option value="`+ selected_data.material +`">`+ selected_data.material +`</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="partdesc[]" id="partdesc`+fCount+`" value="`+ selected_data.matdesc +`" class="form-control" readonly>
                            </td>
                            <td>
                                <input type="text" name="quantity[]" class="form-control inputNumber" value="`+ selected_data.openqty +`">
                            </td>
                            <td>
                                <input type="text" name="uoms[]" id="partunit`+fCount+`" value="`+ selected_data.unit +`" class="form-control" readonly>
                            </td>
                            <td>
                                <input type="text" name="unitprice[]" class="form-control inputNumber">
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