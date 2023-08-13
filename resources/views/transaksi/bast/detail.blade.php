@extends('layouts/App')

@section('title', 'Detail BAST')

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
                        <h3 class="card-title">Detail BAST <b>[ {{ $header->no_bast }} ]</b></h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                
                                <div class="form-group">
                                    <label for="doctitle">NO. BAST</label>
                                    <p>{{ $header->no_bast }}</p>
                                </div>  
                                <div class="form-group">
                                    <label>Pemberi:</label> {{$header->pemberi}}
                                </div>
                                <div class="form-group">
                                    <label>Penerima:</label> {{$header->penerima}}
                                </div>                                
                                <div class="form-group">
                                    <label>Tanggal BAST:</label>
                                    <p>{!! formatDate($header->tanggal_bast) !!}
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label>BAST Created Date:</label>
                                    <p>{!! formatDateTime($header->createdon) !!}
                                    </p>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-sm btn-default form-control" onclick="previewFile('files/BAST/{{$file->efile ?? ''}}#toolbar=0')">
                                        <i class="fa fa-search"></i> Preview Attachment
                                    </button>
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
                    
                    <div class="card-tools">
                        <a href="{{ url('/logistic/bast/listbast') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="tbl-pr-data" class="table table-bordered table-hover table-striped table-sm">
                                <thead>
                                    <th>No</th>
                                    <th>Part Number</th>
                                    <th>Description</th>
                                    <th style="text-align:center;">Quantity</th>
                                    <th>Unit</th>
                                    <th>PBJ Number</th>
                                </thead>
                                <tbody>
                                @foreach($items as $key => $row)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>
                                            {{ $row->material }}
                                        </td>
                                        <td>
                                            {{ $row->matdesc }}
                                        </td>
                                        <td style="text-align:right;">
                                            {{ number_format($row->quantity,3) }}
                                        </td>
                                        <td>
                                            {{ $row->unit }}
                                        </td>
                                        <td>
                                            {{ $row->refdoc }}
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
                @if(userAllowDownloadDocument() == 1)
                <a href="#" id="btnDownloadFile" class="btn btn-default btnDownloadFile" download="">
                    <i class="fa fa-download"></i> Download Document
                </a>
                @endif
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
            
            @if(userAllowDownloadDocument() == 1)
                // document.getElementById("btnDownloadFile").href=base_url+fileUri; 
                document.getElementById("btnDownloadFile").href=fileUri; 
            @endif
            $('#modalPreviewFile').modal('show');
        } else{
            swal("File Not Found", "", "warning");
        }
    }

    $(document).ready(function () { 
       
    });
</script>
@endsection