@extends('layouts/App')

@section('title', 'Document Approval')

@section('additional-css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('/assets/css/customstyle.css') }}">
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
        <div class="col-lg-3">
            <form action="{{ url('transaction/document/save') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Approve PR <b>[ {{ $prhdr->prnum }} ]</b></h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="form-group">
                                    <label for="doctitle">PR Number</label>
                                    <p>{{ $prhdr->prnum }}</p>
                                    <input type="hidden" id="prNumber" value="{{ $prhdr->prnum }}">
                                </div>
                                <div class="form-group">
                                    <label>Requestor:</label> {{$prhdr->requestby}}
                                </div>
                                <div class="form-group">
                                    <label>Project:</label> {{$prhdr->nama_project}}
                                </div>
                                <div class="form-group">
                                    <label>Created Date:</label>
                                    <p>{!! formatDateTime($prhdr->createdon) !!}
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label>Remark</label>
                                    <p>{!! $prhdr->remark !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header">
                    <!-- <h3 class="card-title">Approve Document</h3> -->
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
                    <div class="card-tools">
                        <a href="{{ url('/approve/pr') }}" class="btn btn-default btn-sm">
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
                                            <table id="tbl-pr-data" class="table table-bordered table-hover table-striped table-sm">
                                                <thead>
                                                    {{-- <th>No</th> --}}
                                                    {{-- <th style="text-align:center;">
                                                        <input type="checkbox" id="checkAll" class="filled-in" />
                                                        <label for="checkAll"></label>
                                                    </th> --}}
                                                    <th>PR Item</th>
                                                    <th>Item Code</th>
                                                    <th style="text-align:center;">Quantity</th>
                                                    <th>Unit</th>
                                                    <th>Remark</th>
                                                </thead>
                                                <tbody>
                                                @foreach($pritem as $key => $row)
                                                    <tr>
                                                        <td>
                                                            {{ $row->pritem }}
                                                        </td>
                                                        <td>
                                                            {{ $row->material }} - {{ $row->matdesc }} <br>
                                                            {{ $row->matspec }}
                                                        </td>
                                                        <td style="text-align:right;">
                                                            {{ number_format($row->quantity,0) }}
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
                                                <tfoot>
                                                    <td colspan="8" style="text-align: right;">
                                                        <button type="button" class="btn btn-success pull-right ml-1 btn-sm" id="btn-approve-items">
                                                            <i class="fa fa-check"></i> APPROVE
                                                        </button>

                                                        <button type="button" class="btn btn-danger pull-right btn-sm" id="btn-reject-items">
                                                            <i class="fa fa-xmark"></i> REJECT
                                                        </button>
                                                    </td>
                                                </tfoot>
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

<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modalApprovalNote">
    <div class="modal-dialog modal-lg">
        <form class="form-horizontal" id="form-submit-approval" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalApprovalTitle">Approval Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="position-relative row form-group">
                        <div class="col-lg-12">
                            <textarea name="approvernote" id="approver_note" cols="30" rows="3" class="form-control" placeholder="Approval Note..."></textarea>
                        </div>
                        <div class="col-lg-12">
                            <label for="Attachment">Attachment</label>
                            <input type="file" class="form-control" name="efile[]" multiple="multiple">
                            <input type="hidden" name="action" id="_action">
                            <input type="hidden" name="prid" id="prid" value="{{ $prhdr->id }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary submit-approval"> OK</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('additional-js')
<script src="{{ asset('/assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('/assets/ckeditor/adapters/jquery.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- <script src="https://cdn.scaleflex.it/plugins/filerobot-image-editor/3/filerobot-image-editor.min.js"></script> -->

<script type="text/javascript">
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

    $(document).ready(function () {
        let _token   = $('meta[name="csrf-token"]').attr('content');
        let _action  = null;

        $('#btn-approve-items').on('click', function(){

            _action = 'A';
            $('#_action').val('A');
            $('#modalApprovalTitle').html('Approve Note');
            $('#modalApprovalNote').modal('show');
        });

        $('#btn-reject-items').on('click', function(){

            _action = 'R';
            $('#_action').val('R');
            $('#modalApprovalTitle').html('Reject Note');
            $('#modalApprovalNote').modal('show');
        });

        $('#form-submit-approval').on('submit', function(event){
            event.preventDefault();
            var formData = new FormData(this);
            console.log($(this).serialize())
            $.ajax({
                url:base_url+'/approve/pr/save',
                method:'post',
                data:formData,
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend:function(){
                    $('.submit-approval').attr('disabled','disabled');
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
                        window.location.href = base_url+'/approve/pr';
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
