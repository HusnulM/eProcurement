@extends('layouts/App')

@section('title', 'Create BAST | List PBJ')

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
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List PBJ</h3>
                    <div class="card-tools">
                        
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="tbl-pbj-list" class="table table-bordered table-hover table-striped table-sm">
                                <thead>
                                    <th>No</th>
                                    <th>Nomor PBJ</th>
                                    <th>Tanggal PBJ</th>
                                    <th>Department</th>
                                    <th>Tujuan Permintaan</th>
                                    <th>Kepada</th>
                                    <th>Unit Desc / Code</th>
                                    <th>Engine Model</th>
                                    <th></th>
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
@endsection

@section('additional-modal')

@endsection

@section('additional-js')
<script src="{{ asset('/assets/js/select2.min.js') }}"></script>
<script>    
    $(document).ready(function(){
        var count = 0;
        let selected_pr_items = [];
        let selected_items    = [];
        let _token   = $('meta[name="csrf-token"]').attr('content');

        loadPBJ();

        var fCount = 0;
        function loadPBJ(){
            $("#tbl-pbj-list").DataTable({
                serverSide: true,
                ajax: {
                    url: base_url+'/logistic/bast/databast',
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
                    {data: "pbjnumber", className: 'uid'},
                    {data: "tgl_pbj", className: 'fname'},
                    {data: "department", className: 'fname'},
                    {data: "tujuan_permintaan", className: 'fname'},
                    {data: "kepada", className: 'fname'},
                    {data: "unit_desc", className: 'fname'},
                    {data: "engine_model", className: 'fname'},
                    {"defaultContent": 
                        "<button type='button' class='btn btn-primary btn-sm button-create-bast'> <i class='fa fa-plus'></i> Create BAST</button>",
                        "className": "text-center",
                    }
                ],
                "bDestroy": true,
            });

            $("#tbl-pbj-list tbody").on('click', '.button-create-bast', function(){
                var menuTable = $('#tbl-pbj-list').DataTable();
                selected_data = [];
                selected_data = menuTable.row($(this).closest('tr')).data();
                window.location = base_url+"/logistic/bast/create/"+selected_data.id;                
            });
        }

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