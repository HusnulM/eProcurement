@extends('layouts/App')

@section('title', 'Update Purchase Request')

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
                        <h3 class="card-title">Update Purchase Request</h3>
                        <div class="card-tools">
                            {{-- <a href="{{ url('/printdoc/pr/print') }}/{{ $prhdr->id}}" target="_blank" class='btn btn-success btn-sm button-print'>
                                <i class='fa fa-print'></i> Print
                            </a> --}}
                            <a href="{{ url('/proc/pr/list') }}" class='btn btn-default btn-sm btn-update-pr'>
                                <i class='fa fa-arrow-left'></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm btn-update-pr">
                                <i class="fas fa-save"></i> Update Purchase Request
                            </button>

                            <a href="{{ url('/proc/pr/delete') }}/{{ $prhdr->id }}" class='btn btn-danger btn-sm btn-update-pr'>
                                <i class='fa fa-trash'></i> Hapus Purchase Request
                            </a>
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
                                                                        <th>Item Code</th>
                                                                        <th>Quantity</th>
                                                                        <th>Unit</th>
                                                                        <th>Remark</th>
                                                                        <th>
                                                                            <button type="button" class="btn btn-success btn-sm btn-add-pbj-item">
                                                                                <i class="fa fa-plus"></i>
                                                                            </button>
                                                                        </th>
                                                                    </thead>
                                                                    <tbody id="tbl-pbj-body">
                                                                        @foreach($pritem as $key => $row)
                                                                        <tr>
                                                                            <td>
                                                                                {{ $row->matdesc }} <br>
                                                                                {{ $row->matspec }}
                                                                                <input type="hidden" class="form-control" name="pritem[]" value="{{ $row->pritem }}">
                                                                                <input type="hidden" class="form-control" name="material[]" value="{{ $row->material }}">
                                                                                <input type="hidden" class="form-control" name="matdesc[]" value="{{ $row->matdesc }}">
                                                                            </td>
                                                                            <td style="text-align:right;">
                                                                                <input type="text" class="form-control" value="{{ number_format($row->quantity,0) }}" name="quantity[]" style="text-align:right;">
                                                                            </td>
                                                                            <td>
                                                                                {{ $row->unit }}
                                                                                <input type="hidden" class="form-control" name="unit[]" value="{{ $row->unit }}">
                                                                            </td>
                                                                            <th>
                                                                                <input type="text" class="form-control" name="remarks[]" value="{{ $row->remark }}">
                                                                            </th>
                                                                            <td style="text-align: center;">
                                                                                <button type="button" class="btn btn-danger btn-sm btn-delete-item" data-prnum="{{ $row->prnum }}" data-pritem="{{ $row->pritem }}">
                                                                                    <i class="fa fa-trash"></i>
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
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Item Spek</th>
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
        let selected_pbj_items = [];
        let selected_items = [];
        var count = 0;

        let _token   = $('meta[name="csrf-token"]').attr('content');

        $('.btn-delete-item').on('click', function(){
            var _adata = $(this).data();
            console.log(_adata)
            // let _token   = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: base_url+'/proc/pr/deleteitem',
                type:"POST",
                data:{
                    prnum: _adata.prnum,
                    pritem: _adata.pritem,
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
                            window.location.href = base_url+'/proc/pr/change/{{ $prhdr->id }}';
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

        $('.btn-select-pbj').on('click', function(){
            loadListPBJ();
            $('#modal-list-pbj').modal('show');
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
                    {data: "matspec"},
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
                    if(selected_data.matspec == null || selected_data.matspec == 'null'){
                        selected_data.matspec = '';
                    }
                    fCount = fCount + 1;
                    $('#tbl-pbj-body').append(`
                        <tr>
                            <td>
                                `+selected_data.material+` - `+ selected_data.matdesc +` <br> `+ selected_data.matspec +`
                                <input type="hidden" name="material[]" id="parts`+fCount+`" class="form-control" value="`+ selected_data.material +`" readonly>
                                <input type="hidden" name="matdesc[]" id="partdesc`+fCount+`" class="form-control" value="`+ selected_data.matdesc +`" readonly>
                            </td>

                            <td>
                                <input type="text" name="quantity[]" class="form-control inputNumber" style="text-align:right;" required>
                            </td>
                            <td>
                                `+ selected_data.matunit +`
                                <input type="hidden" name="unit[]" id="partunit`+fCount+`" value="`+ selected_data.matunit +`" class="form-control" readonly>
                            </td>
                            <td>
                                <input type="text" name="remarks[]" class="form-control" >
                                <input type="hidden" class="form-control" name="pritem[]">
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

        $('#form-submit-data').on('submit', function(event){
            event.preventDefault();
            // action="{{ url('proc/pr/update') }}/{{ $prhdr->id }}"
            var formData = new FormData(this);
            console.log($(this).serialize())
            $.ajax({
                url:base_url+'/proc/pr/update/{{ $prhdr->id }}',
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
                        window.location.href = base_url+'/proc/pr/change/{{ $prhdr->id }}';
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
