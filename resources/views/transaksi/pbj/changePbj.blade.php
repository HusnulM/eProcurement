@extends('layouts/App')

@section('title', 'Change PBJ')

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
    <form action="{{ url('/transaction/pbj/udpate') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail PBJ <b>[ {{ $pbjhdr->pbjnumber }} ]</b></h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="tglpbj">Tanggal PBJ</label>
                                            <input type="date" name="tglpbj" class="form-control" value="{{ $pbjhdr->tgl_pbj }}" required>
                                            <input type="hidden" name="pbjnumber" value="{{ $pbjhdr->pbjnumber }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="requestto">Tujuan Permintaan</label>
                                            <input type="text" name="requestto" class="form-control" value="{{ $pbjhdr->tujuan_permintaan }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="currency">Warehouse</label>                                            
                                            <select name="whscode" id="find-whscode" class="form-control" readonly>
                                                <option value="{{ $pbjwhs->id }}">{{ $pbjwhs->whsname }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="kepada">Kepada</label>
                                            <!-- <input type="text" name="kepada" class="form-control"> -->
                                            <select name="kepada" id="kepada" class="form-control" required>
                                                <option value="{{ $pbjhdr->kepada }}">{{ $pbjhdr->kepada }}</option>
                                                @foreach($department as $key => $row)
                                                    <option value="{{ $row->department }}">{{ $row->department }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="unitdesc">Unit Desc / Code</label>
                                            <select name="unitdesc" id="find-unitdesc" class="form-control">
                                                <option value="{{ $kendaraan->no_kendaraan ?? '' }}">{{ $kendaraan->no_kendaraan ?? '' }} - {{ $kendaraan->model_kendaraan ?? '' }}</option>
                                            </select>
                                            <!-- <input type="text" name="unitdesc" class="form-control"> -->
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="engine">Engine Model</label>
                                            <input type="text" name="engine" id="engine_model" class="form-control" value="{{ $pbjhdr->engine_model }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="chassis">Chassis S/N</label>
                                            <input type="text" name="chassis" id="chassis" class="form-control" value="{{ $pbjhdr->chassis_sn }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="refrensi">Refrensi Permintaan</label>
                                            <input type="text" name="refrensi" class="form-control" value="{{ $pbjhdr->reference }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="requestor">Requestor</label>
                                            <input type="text" name="requestor" class="form-control" value="{{ Auth::user()->name }}">
                                        </div>
                                    </div>                                    
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="typeModel">Type / Model</label>
                                            <input type="text" name="typeModel" id="typeModel" class="form-control" value="{{ $pbjhdr->type_model }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="user">User</label>
                                            <!-- <input type="text" name="user" class="form-control"> -->
                                            <select name="user" id="user" class="form-control">
                                                <option value="{{ $pbjhdr->user }}">{{ $pbjhdr->user }}</option>
                                                @foreach($mekanik as $key => $row)
                                                    <option value="{{ $row->nama }}">{{ $row->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>        
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="kodeJasa">Kode Barang / Jasa</label>
                                            <input type="text" name="kodeJasa" class="form-control" value="{{ $pbjhdr->kode_brg_jasa }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="nginesn">Engine S/N</label>
                                            <input type="text" name="nginesn" id="nginesn" class="form-control" value="{{ $pbjhdr->engine_sn }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="hmkm">HM</label>
                                            <input type="text" name="hmkm" id="hmkm" class="form-control" value="{{ $pbjhdr->hm_km }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="km">KM</label>
                                            <input type="text" name="km" id="km" class="form-control" value="{{ $pbjhdr->km }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="budgetcode">Budget / Cost Code</label>
                                            {{-- <input type="text" name="budgetcode" class="form-control" value="{{ $pbjhdr->budget_cost_code }}"> --}}
                                            <select name="budgetcode" class="form-control">
                                                <option value="{{ $pbjhdr->budget_cost_code }}">{{ $pbjhdr->budget_cost_code }}</option>
                                                <option value="1">1</option>
                                                <option value="0">0</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="no_rangka">No. Rangka</label>
                                            <input type="text" name="no_rangka" id="no_rangka" class="form-control" value="{{ $pbjhdr->chassis_sn }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="periode">Periode</label>
                                            <select name="periode" class="form-control">
                                                <option value="{{ $pbjhdr->periode }}">{{ $pbjhdr->periode }}</option>
                                                <option value="Januari <?= date('Y'); ?>">Januari <?= date('Y'); ?></option>
                                                <option value="Februari <?= date('Y'); ?>">Februari <?= date('Y'); ?></option>
                                                <option value="Maret <?= date('Y'); ?>">Maret <?= date('Y'); ?></option>
                                                <option value="April <?= date('Y'); ?>">April <?= date('Y'); ?></option>
                                                <option value="Mei <?= date('Y'); ?>">Mei <?= date('Y'); ?></option>
                                                <option value="Juni <?= date('Y'); ?>">Juni <?= date('Y'); ?></option>
                                                <option value="Juli <?= date('Y'); ?>">Juli <?= date('Y'); ?></option>
                                                <option value="Agustus <?= date('Y'); ?>">Agustus <?= date('Y'); ?></option>
                                                <option value="September <?= date('Y'); ?>">September <?= date('Y'); ?></option>
                                                <option value="Oktober <?= date('Y'); ?>">Oktober <?= date('Y'); ?></option>
                                                <option value="November <?= date('Y'); ?>">November <?= date('Y'); ?></option>
                                                <option value="Desember <?= date('Y'); ?>">Desember <?= date('Y'); ?></option>
                                            </select>
                                        </div>
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="remark">Remark</label>
                                            <textarea name="remark"cols="30" rows="3" class="form-control" placeholder="Remark...">{!! $pbjhdr->remark !!}</textarea>
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
                            {{-- <div class="col-lg-12">
                                
                                <div class="form-group">
                                    <label for="doctitle">PBJ Number</label>
                                    <p>{{ $pbjhdr->pbjnumber }}</p>
                                    <input type="hidden" id="pbjNumber" value="{{ $pbjhdr->pbjnumber }}">
                                </div>  
                                <div class="form-group">
                                    <label>Checklist No:</label> {{$pbjhdr->cheklistnumber}}
                                </div>
                                <div class="form-group">
                                    <label>Created By:</label> {{$pbjhdr->createdby}}
                                </div>
                                <div class="form-group">
                                    <label>Created Date:</label>
                                    <p>{!! formatDateTime($pbjhdr->createdon) !!}
                                    </p>
                                </div>
                            </div>   --}}
                        </div>
                    </div>
                </div>
            
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    
                    <div class="card-tools">
                        {{-- <a href="{{ url('/printdoc/pbj/print/') }}/{{ $pbjhdr->id}}" target="_blank" class='btn btn-success btn-sm button-print'> 
                            <i class='fa fa-print'></i> Print
                        </a> --}}
                        @if($pbjhdr->pbj_status === "A")
                            <b style="color: red;">PBJ Sudah di Approve, tidak bisa diupdate!</b>
                        @else
                        <button type="submit" class="btn btn-primary btn-sm btn-add-dept">
                            <i class="fas fa-save"></i> Update PBJ
                        </button>
                        @endif
                        <a href="{{ url('/transaction/pbj/list') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-header">
                                <div class="row">
                                    <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">PBJ Items</a>
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
                                                        <table id="tbl-pbj" class="table table-bordered table-hover table-striped table-sm">
                                                            <thead>
                                                                {{-- <th>No</th> --}}
                                                                {{-- <th>PBJ Item</th> --}}
                                                                <th>Part Number</th>
                                                                {{-- <th>Description</th> --}}
                                                                <th style="text-align:center;">Quantity</th>
                                                                <th>Unit</th>
                                                                <th>Figure</th>
                                                                <th>Remark</th>
                                                                <th>
                                                                    <button type="button" class="btn btn-success btn-sm btn-add-pbj-item">
                                                                        <i class="fa fa-plus"></i>
                                                                    </button>
                                                                </th>
                                                            </thead>
                                                            <tbody id="tbl-pbj-body">
                                                                @foreach($pbjitem as $key => $row)
                                                                    <tr>
                                                                        <td>
                                                                            {{ $row->partnumber }} - {{ $row->description }}
                                                                            <input type="hidden" name="parts[]" id="parts`+fCount+`" class="form-control" value="{{ $row->partnumber }}" readonly>
                                                                            <input type="hidden" name="partdesc[]" id="partdesc`+fCount+`" class="form-control" value="{{ $row->description }}" readonly>
                                                                        </td>
                                                                        <td style="text-align:right;">
                                                                            <input type="text" name="quantity[]" value="{{ number_format($row->quantity,0) }}" class="form-control" style="text-align: right;">
                                                                        </td>
                                                                        <td>
                                                                            {{ $row->unit }}
                                                                            <input type="hidden" name="uoms[]" class="form-control" value="{{ $row->unit }}" required>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="figures[]" class="form-control" value="{{ $row->figure }}">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="remarks[]" class="form-control" value="{{ $row->remark }}">
                                                                            <input type="hidden" name="pbjitem[]" class="form-control" value="{{ $row->pbjitem }}">
                                                                        </td>
                                                                        <td style="text-align: center;">
                                                                            <button type="button" class="btn btn-danger btn-sm btn-delete-item" data-pbjnumber="{{ $row->pbjnumber }}" data-pbjitem="{{ $row->pbjitem }}">
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
                                                                <th>PBJ Item</th>
                                                                <th>Approval Status</th>
                                                                <th>Approve/Reject Date</th>
                                                                {{-- <th>Approver Note</th> --}}
                                                            </thead>
                                                            <tbody>
                                                                @foreach($approvals as $key => $row)
                                                                <tr>
                                                                    <td>{{ $row->approver_name }}</td>
                                                                    <td>{{ $row->approver_level }}</td>
                                                                    <td>{{ $row->pbjitem }}</td>
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
                                                                    {{-- <td>{!! $row->approval_remark !!}</td> --}}
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
                                                                        <button type="button" class="btn btn-sm btn-default" onclick="previewFile('files/PBJ/{{$file->efile}}#toolbar=0')">
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
                            {{-- <th>Part Number</th> --}}
                            {{-- <th>Warehouse</th>
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
        // $('#tbl-pbj').DataTable();

        $('#btn-approve').on('click', function(){
            $('#btn-approve').prop('disabled', true);
            $('#btn-reject').prop('disabled', true);
            approveDocument('A');
        });

        $('#btn-reject').on('click', function(){
            $('#btn-approve').prop('disabled', true);
            $('#btn-reject').prop('disabled', true);
            approveDocument('R');
        });

        function approveDocument(_action){
            let _token   = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: base_url+'/approve/pbj/save',
                type:"POST",
                data:{
                    pbjNumber: "{{ $pbjhdr->pbjnumber }}",
                    action:_action,
                    approvernote:$('#approver_note').val(),
                    _token: _token
                },
                success:function(response){
                    console.log(response);
                    if(response.msgtype === "200"){
                        if(_action === "A"){
                            toastr.success(response.message)
                        }else if(_action === "R"){
                            toastr.success(response.message)
                        }                        

                        setTimeout(function(){ 
                            window.location.href = base_url+'/approve/pbj';
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
            });
        }

        var count = 0;

        let _token   = $('meta[name="csrf-token"]').attr('content');
        let selected_items = [];
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
                                <input type="text" name="quantity[]" class="form-control" style="text-align:right;" required>
                            </td>
                            <td>
                                `+ selected_data.matunit +`
                                <input type="hidden" name="uoms[]" id="partunit`+fCount+`" value="`+ selected_data.matunit +`" class="form-control" readonly>
                            </td>
                            <td>
                                <input type="text" name="figures[]" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="remarks[]" class="form-control">
                                <input type="hidden" name="warehouse[]" class="form-control" value="`+ $('#find-whscode').val() +`">
                                <input type="hidden" name="wonum[]" class="form-control">
                                <input type="hidden" name="woitem[]" class="form-control">
                                <input type="hidden" name="pbjitem[]" class="form-control">
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
            // alert($('#find-whscode').val())
            if($('#find-whscode').val() === '' || $('#find-whscode').val() == null){
                toastr.error("Pilih warehouse");
            }else{
                loadMaterial();
                $('#modal-add-material').modal('show');
            }
        });

        $('.btn-delete-item').on('click', function(){
            var _adata = $(this).data();
            console.log(_adata)
            // let _token   = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: base_url+'/transaction/pbj/deleteitem',
                type:"POST",
                data:{
                    pbjnumber: _adata.pbjnumber,
                    pbjitem: _adata.pbjitem,
                    _token: _token
                },
                beforeSend:function(){
                    $('.btn-delete-item').attr('disabled','disabled');
                    // showBasicMessage();
                },
                success:function(response){
                    
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
                if(response.msgtype === "200"){

                    toastr.success(response.message)
                    setTimeout(function(){ 
                            location.reload();
                        }, 2000);
                }else{
                    toastr.error(response.message);
                    setTimeout(function(){ 
                            location.reload();
                        }, 2000);
                }                
                // $(this).closest("tr").remove();
            });
        });

        // $('#find-whscode').select2({ 
        //     placeholder: 'Ketik Nama Gudang',
        //     width: '100%',
        //     minimumInputLength: 0,
        //     ajax: {
        //         url: base_url + '/master/warehouse/findwhs',
        //         dataType: 'json',
        //         delay: 250,
        //         method: 'POST',
        //         headers: {
        //             'X-CSRF-TOKEN': _token
        //         },
        //         data: function (params) {
        //             var query = {
        //                 search: params.term,
        //                 // custname: $('#find-customer').val()
        //             }
        //             return query;
        //         },
        //         processResults: function (data) {
        //             // return {
        //             //     results: response
        //             // };
        //             console.log(data)
        //             return {
        //                 results: $.map(data.data, function (item) {
        //                     return {
        //                         text: item.whsname,
        //                         slug: item.whsname,
        //                         id: item.whscode,
        //                         ...item
        //                     }
        //                 })
        //             };
        //         },
        //         cache: true
        //     }
        // }); 
    });
</script>
@endsection