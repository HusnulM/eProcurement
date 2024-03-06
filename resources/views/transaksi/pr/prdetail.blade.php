@extends('layouts/App')

@section('title', 'Detail Purchase Request')

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
    <form action="{{ url('proc/pr/save') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Purchase Request</h3>
                        <div class="card-tools">
                            <a href="{{ url('/printdoc/pr/print') }}/{{ $prhdr->id}}" target="_blank" class='btn btn-success btn-sm button-print'>
                                <i class='fa fa-print'></i> Print
                            </a>
                            <a href="{{ url('/proc/pr/change') }}/{{ $prhdr->id}}" class='btn btn-primary btn-sm'>
                                <i class='fa fa-edit'></i> Change
                            </a>
                            <a href="{{ url('/proc/pr/listpr') }}" class='btn btn-default btn-sm'>
                                <i class='fa fa-arrow-left'></i> Back
                            </a>

                            <!-- <button type="submit" class="btn btn-primary btn-sm btn-add-dept">
                                <i class="fas fa-save"></i> Simpan Purchase Request
                            </button> -->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2 col-md-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="prnum">Nomor PR</label>
                                            <input type="text" name="prnum" class="form-control" value="{{ $prhdr->prnum }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="requestor">Type PR</label>
                                            <select name="prtype" id="prtype" class="form-control" required>
                                                @if($prhdr->typepr === 'AA')
                                                    <option value="AA">Head Office</option>
                                                @else
                                                    <option value="SQ">Proyek</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="tglreq">Tanggal Request</label>
                                            <input type="date" name="tglreq" class="form-control" required value="{{ $prhdr->prdate }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="requestor">Requestor</label>
                                            <input type="text" name="requestor" class="form-control" value="{{ $prhdr->requestby }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="requestor">Proyek</label>
                                            <select name="project" id="proyek" class="form-control" required>
                                                <option value="{{ $proyekx->id }}">{{ $proyekx->kode_project }} - {{ $proyekx->nama_project }}</option>
                                                @foreach ($proyek as $ls => $row)
                                                    <option value="{{ $row->id }}">{{ $row->kode_project }} - {{ $row->nama_project }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="remark">Remark</label>
                                            <textarea type="text" name="remark" id="remark" cols="30" rows="4" class="form-control" placeholder="Remark..." style="white-space: pre-wrap;">{{ trim($prhdr->remark) }}</textarea>
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
                                                    <a class="nav-link active" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">PR Items</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-content-above-approval-tab" data-toggle="pill" href="#custom-content-above-approval" role="tab" aria-controls="custom-content-above-approval" aria-selected="false">Approval Status</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-content-above-attachment-tab" data-toggle="pill" href="#custom-content-above-attachment" role="tab" aria-controls="custom-content-above-attachment" aria-selected="false">Attachment</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="tab-content" id="custom-content-above-tabContent">
                                                    <div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <table class="table table-sm">
                                                                    <thead>
                                                                        <th>Item Code</th>
                                                                        <th>Quantity</th>
                                                                        <th>Unit</th>
                                                                        <th>Remark</th>
                                                                    </thead>
                                                                    <tbody id="tbl-pbj-body">
                                                                        @foreach($pritem as $key => $row)
                                                                        <tr>
                                                                            <td>
                                                                                {{ $row->material }} - {{ $row->matdesc }} <br>
                                                                                {{ $row->matspec }}
                                                                            </td>
                                                                            <td style="text-align:right;">
                                                                                {{ number_format($row->quantity, 0, ',', '.') }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $row->unit }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $row->remark }}
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="custom-content-above-approval" role="tabpanel" aria-labelledby="custom-content-above-approval-tab">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <table id="tbl-approval" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                                                    <thead>
                                                                        <th>Approver Name</th>
                                                                        <th>Approver Level</th>
                                                                        <th>Approval Status</th>
                                                                        <th>Approve/Reject Date</th>
                                                                        <th>Approver Note</th>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($approvals as $key => $row)
                                                                        <tr>
                                                                            <td>{{ $row->approver_name }}</td>
                                                                            <td>{{ $row->approver_level }}</td>
                                                                            @if($row->approval_status == "A")
                                                                            <td style="text-align:center; background-color:green; color:white;">
                                                                                Approved
                                                                            </td>
                                                                            @elseif($row->approval_status == "R")
                                                                            <td style="text-align:center; background-color:red; color:white;">
                                                                                Rejected
                                                                            </td>
                                                                            @else
                                                                            <td style="text-align:center; background-color:yellow; color:black;">
                                                                                Open
                                                                            </td>
                                                                            @endif

                                                                            <td>
                                                                                @if($row->approval_date != null)
                                                                                    <i class="fa fa-clock"></i>
                                                                                    ({{ formatDateTime($row->approval_date) }})
                                                                                @endif
                                                                            </td>
                                                                            <td>{!! $row->approval_remark !!}</td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane fade" id="custom-content-above-attachment" role="tabpanel" aria-labelledby="custom-content-above-attachment-tab">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <table class="table table-sm">
                                                                    <thead>
                                                                        <th>No</th>
                                                                        <th>Object</th>
                                                                        <th>File Name</th>
                                                                        <th>Upload Date</th>
                                                                        <th></th>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($attachments as $key => $file)
                                                                        <tr>
                                                                            <td>{{ $key+1 }}</td>
                                                                            <td>
                                                                                {{ $file->doc_object }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $file->efile }}
                                                                            </td>
                                                                            <td>
                                                                                <i class="fa fa-clock"></i> {!! formatDateTime($file->createdon) !!}
                                                                            </td>
                                                                            <td>
                                                                                <button type="button" class="btn btn-sm btn-default" onclick="previewFile('files/PR/{{$file->efile}}#toolbar=0')">
                                                                                    <i class="fa fa-search"></i> Preview File
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
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
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modalPreviewFile">
    <div class="modal-dialog modal-xl">
        <form class="form-horizontal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPreviewFileTitle">Preview Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="position-relative row form-group">
                    <div class="col-lg-12" id="fileViewer">
                        <!-- <div id="example1"></div> -->

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"> Close</button>
                <a href="#" id="btnDownloadFile" class="btn btn-default btnDownloadFile" download="">
                    <i class="fa fa-download"></i> Download Document
                </a>
            </div>
        </div>
        </form>
    </div>
</div>
@endsection

@section('additional-js')
<script src="{{ asset('/assets/js/select2.min.js') }}"></script>
<script>
    function previewFile(files){
        // alert(base_url)
        var pathfile = base_url+'/'+files;
        if(files !== ""){
            $('#fileViewer').html('');
            $('#fileViewer').append(`
                <embed src="`+ pathfile +`" frameborder="0" width="100%" height="500px">

            `);

            var fileUri = pathfile;
            fileUri = fileUri.replace("#toolbar=0", "?force=true");

            document.getElementById("btnDownloadFile").href=fileUri;
            $('#modalPreviewFile').modal('show');
        } else{
            swal("File Not Found", "", "warning");
        }
    }

    $(document).ready(function(){
        var count = 0;

        let _token   = $('meta[name="csrf-token"]').attr('content');

        $('.btn-select-pbj').on('click', function(){
            loadListPBJ();
            $('#modal-list-pbj').modal('show');
        });

        var fCount = 0;

        // <td>
        //                 <input type="text" name="partdesc[]" id="partdesc`+fCount+`" class="form-control">
        //             </td>

        $('.btn-add-pbj-item').on('click', function(){
            fCount = fCount + 1;
            $('#tbl-pbj-body').append(`
                <tr>
                    <td>
                        <select name="parts[]" id="find-part`+fCount+`" class="form-control"></select>
                        <label id="lbldesc`+fCount+`"></lable>
                        <input type="hidden" name="partdesc[]" id="partdesc`+fCount+`" class="form-control">
                    </td>

                    <td>
                        <input type="text" name="quantity[]" class="form-control inputNumber" required>
                    </td>
                    <td>
                        <input type="text" name="uoms[]" id="partunit`+fCount+`" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="pbjref[]" id="pbjref`+fCount+`" class="form-control">
                        <input type="hidden" name="pbjnum[]" id="pbjnum`+fCount+`" class="form-control">
                        <input type="hidden" name="pbjitm[]" id="pbjitm`+fCount+`" class="form-control">
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
                $('#lbldesc').html(data[0].partname);
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

            $('.inputNumber').on('keypress', function(e){
                validate(e);
            });

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

        // <th>Part Number</th>
        //                         <th>Part Name</th>
        //                         <th>Quantity</th>
        //                         <th>Unit</th>
        //                         <th>Figure</th>
        //                         <th>Remark</th>

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

                console.log(selected_data);
                // <td>
                //             <input type="text" name="partdesc[]" id="partdesc`+fCount+`" class="form-control" value="`+selected_data.description+`" readonly>
                //         </td>
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
                        </td>
                        <td>
                            <input type="text" name="uoms[]" id="partunit`+fCount+`" class="form-control" value="`+selected_data.unit+`" readonly>
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
            });

        }
    });
</script>
@endsection
