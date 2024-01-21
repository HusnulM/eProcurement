@extends('layouts/App')

@section('title', 'Subtitut Approved Purchase Request')

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
    {{-- action="{{ url('proc/pr/update') }}/{{ $prhdr->id }}"  --}}
    <form id="form-submit-data" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Subtitut Approved Purchase Request</h3>
                        <div class="card-tools">
                            {{-- <a href="{{ url('/printdoc/pr/print') }}/{{ $prhdr->id}}" target="_blank" class='btn btn-success btn-sm button-print'>
                                <i class='fa fa-print'></i> Print
                            </a> --}}
                            <a href="{{ url('/proc/pr/changeapprovedpr') }}" class='btn btn-default btn-sm btn-update-pr'>
                                <i class='fa fa-arrow-left'></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm btn-update-pr">
                                <i class="fas fa-save"></i> Update Purchase Request
                            </button>
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
                                            <label for="tglreq">Tanggal Request</label>
                                            <input type="date" name="tglreq" class="form-control" value="{{ $prhdr->prdate }}" required>
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
                                            <label for="department">Department</label>
                                            <input type="text" name="department" class="form-control" value="{{ getUserDepartment() }}" readonly>
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
                                        {{-- <div class="card-tools">
                                            <a href="{{ url('/proc/pr/listpr') }}" class="btn btn-success btn-sm">
                                                <i class="fa fa-list"></i> List PR
                                            </a>
                                        </div> --}}
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
                                                                        <th>Part No. / Type</th>
                                                                        <th style="text-align:right;">Quantity</th>
                                                                        <th>Unit</th>
                                                                        <th>PBJ Reference</th>
                                                                        <th>
                                                                            {{-- <button type="button" class="btn btn-success btn-sm btn-add-pbj-item">
                                                                                <i class="fa fa-plus"></i>
                                                                            </button>
                                                                            <button type="button" class="btn btn-success btn-sm btn-select-pbj">
                                                                                <i class="fa fa-list"></i> List PBJ
                                                                            </button> --}}
                                                                        </th>
                                                                    </thead>
                                                                    <tbody id="tbl-pbj-body">
                                                                        @foreach($pritem as $key => $row)
                                                                        <tr>
                                                                            <td>
                                                                                {{ $row->material }} - {{ $row->matdesc }}
                                                                                <input type="hidden" class="form-control" name="pritem[]" value="{{ $row->pritem }}">
                                                                                <input type="hidden" class="form-control" name="material[]" value="{{ $row->material }}">
                                                                                <input type="hidden" class="form-control" name="matdesc[]" value="{{ $row->matdesc }}">
                                                                            </td>
                                                                            <td style="text-align:right;">
                                                                                {{ number_format($row->quantity,3) }}
                                                                                <input type="hidden" class="form-control" value="{{ number_format($row->quantity,3) }}" name="quantity[]" style="text-align:right;">
                                                                            </td>
                                                                            <td>
                                                                                {{ $row->unit }}
                                                                                <input type="hidden" class="form-control" name="unit[]" value="{{ $row->unit }}">
                                                                            </td>
                                                                            <td>
                                                                                {{ $row->pbjnumber }}
                                                                                <input type="hidden" class="form-control" name="pbjnumber[]" value="{{ $row->pbjnumber }}">
                                                                                <input type="hidden" class="form-control" name="pbjitem[]" value="{{ $row->pbjitem }}">
                                                                                <input type="hidden" class="form-control" name="nopol[]" value="{{ $row->no_plat }}">
                                                                            </td>
                                                                            <td style="text-align: center;">
                                                                                <button type="button" class="btn btn-primary btn-sm btn-change-item" data-prnum="{{ $row->prnum }}" data-pritem="{{ $row->pritem }}" data-material="{{ $row->material }}" data-matdesc="{{ $row->matdesc }}" data-uom="{{ $row->unit }}" data-quantity="{{ $row->quantity }}">
                                                                                    <i class="fa fa-edit"></i>
                                                                                </button>
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
                                                                        <th>File Name</th>
                                                                        <th>Upload Date</th>
                                                                        <th></th>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($attachments as $key => $file)
                                                                        <tr>
                                                                            <td>{{ $key+1 }}</td>
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


<div class="modal fade bd-example-modal-xl" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modalChangePRItem">
    <div class="modal-dialog modal-xl">
        <form id="form-subtitut-data" class="form-horizontal">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalChangePRItemTitle">Subtitut PR Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="position-relative row form-group">
                        <input type="hidden" name="oldPrnum" id="oldPrnum">
                        <input type="hidden" name="oldPritem" id="oldPritem">
                        <div class="col-lg-6 col-sm-12">
                            <div class="col-lg-12">
                                <label for="oldMaerial">Old Material</label>
                                <input type="text" name="oldMaerial" id="oldMaerial" class="form-control" readonly>
                            </div>
                            <div class="col-sm-12">
                                <label for="oldMatdesc">Description</label>
                                <input type="text" name="oldMatdesc" id="oldMatdesc" class="form-control" readonly>
                            </div>
                            <div class="col-sm-12">
                                <label for="oldQuantity">Old Quantity</label>
                                <input type="text" name="oldQuantity" id="oldQuantity" class="form-control" readonly>
                            </div>
                            <div class="col-sm-12">
                                <label for="oldUom">Unit</label>
                                <input type="text" name="oldUom" id="oldUom" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-12">
                            <div class="col-lg-12">
                                <label for="newItem">New Material</label>
                                <select name="newMaerial" id="find-vendor" class="form-control" required></select>
                            </div>
                            <div class="col-sm-12">
                                <label for="newDesc">Description</label>
                                <input type="text" name="newMatdesc" id="newMatdesc" class="form-control" readonly>
                            </div>
                            <div class="col-sm-12">
                                <label for="newQty">New Quantity</label>
                                <input type="text" name="newQuantity" id="newQuantity" class="form-control inputNumber" required>
                            </div>
                            <div class="col-sm-12">
                                <label for="newUom">Unit</label>
                                <input type="text" name="newUom" id="newUom" class="form-control" readonly>
                            </div>
                            <div class="col-sm-12">
                                <label for="subTitRemark">Remark</label>
                                <input type="text" name="subTitRemark" id="subTitRemark" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"> Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-submit-change"> Save Subtitut PR Item</button>
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
        let selected_pbj_items = [];
        let selected_items = [];
        var count = 0;

        let _token   = $('meta[name="csrf-token"]').attr('content');

        $('.btn-change-item').on('click', function(){
            var _adata = $(this).data();
            $('#oldMaerial').val(_adata.material);
            $('#oldMatdesc').val(_adata.matdesc);
            $('#oldQuantity').val(_adata.quantity);
            $('#oldUom').val(_adata.uom);
            $('#oldPrnum').val(_adata.prnum);
            $('#oldPritem').val(_adata.pritem);

            $(document).on('select2:open', (event) => {
                const searchField = document.querySelector(
                    `.select2-search__field`,
                );
                if (searchField) {
                    searchField.focus();
                }
            });
            $('#find-vendor').select2({
                placeholder: 'Type Item Name or Itemcode',
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
                                    text: item.material + ' - ' + item.matdesc,
                                    slug: item.matdesc,
                                    id: item.material,
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
                $('#vendor_address').html('');
                var data = $('#find-vendor').select2('data')
                console.log(data);
                $('#newMaterial').val(data[0].material);
                $('#newMatdesc').val(data[0].matdesc);
                $('#newUom').val(data[0].matunit);
                // $('#new').html(data[0].material);
            });

            $('#modalChangePRItemTitle').html('Subtitut PR Item '+ _adata.pritem);

            $('.inputNumber').on('keypress', function(e){
                validate(e);
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


            $('#modalChangePRItem').modal('show');
        });


        $('#form-subtitut-data').on('submit', function(event){
            event.preventDefault();
            // action="{{ url('proc/pr/update') }}/{{ $prhdr->id }}"
            var formData = new FormData(this);
            console.log($(this).serialize())
            $.ajax({
                url:base_url+'/proc/pr/savechangeapprovedpr',
                method:'post',
                data:formData,
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend:function(){
                    $('#btn-submit-change').attr('disabled','disabled');
                    // showBasicMessage();
                },
                success:function(data)
                {
                    console.log(data)
                },
                error:function(error){
                    toastr.error(error)
                    console.log(error)
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                }
            }).done(function(result){
                console.log(result)
                if(result.msgtype === "200"){
                    toastr.success(result.message)
                    setTimeout(function(){
                        window.location.href = base_url+'/proc/pr/changeapprovedpr/{{ $prhdr->id }}';
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
