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
                        <h3 class="card-title">GR PO Number <b>[ {{ $pohdr->docnum }} ]</b></h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="doctitle">GRPO Number</label>
                                    <p>{{ $pohdr->docnum }}</p>
                                    <input type="hidden" id="prNumber" value="{{ $pohdr->docnum }}">
                                </div>  
                                <div class="form-group">
                                    <label>Receipt Date:</label> {{ \Carbon\Carbon::parse($pohdr->postdate)->format('d-m-Y') }}
                                </div>
                                <div class="form-group">
                                    <label>Received By:</label> {{$pohdr->received_by}}
                                </div>
                                <div class="form-group">
                                    <label>Vendor:</label>
                                    <p>{{ $pohdr->vendor_name }}
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label>Remark</label>
                                    <p>{!! $pohdr->remark !!}
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
                                <a class="nav-link active" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">Receipt Items</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-tools">
                        <a href="{{ url('/printdoc/grpo/print') }}/{{ $pohdr->id}}" target="_blank" class='btn btn-success btn-sm button-print'> 
                            <i class='fa fa-print'></i> Print
                        </a>
                        <a href="{{ url('/printdoc/grpo') }}" class="btn btn-default btn-sm">
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
                                                    <th>No</th>
                                                    <th style="width:120px;">Part Number</th>
                                                    <th style="width:300px;">Description</th>
                                                    <th style="text-align:right;">Quantity</th>
                                                    <th style="text-align:center;">Unit</th>
                                                    <th style="text-align:right;">Unit Price</th>
                                                    <th style="text-align:right;">Total Price</th>
                                                </thead>
                                                <tbody>
                                                @foreach($poitem as $key => $row)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $row->material }}</td>
                                                    <td>{{ $row->matdesc }}</td>
                                                    <td style="text-align:right;">
                                                    @if(strpos($row->quantity, '.000') !== false)
                                                    {{ number_format($row->quantity, 0, ',', '.') }}
                                                    @else
                                                    {{ number_format($row->quantity, 3, ',', '.') }}
                                                    @endif                
                                                    </td>
                                                    <td style="text-align:center;">{{ $row->unit }}</td>
                                                    <td style="text-align:right;">
                                                    @if(strpos($row->unit_price, '.000') !== false)
                                                    {{ number_format($row->unit_price, 0, ',', '.') }}
                                                    @else
                                                    {{ number_format($row->unit_price, 3, ',', '.') }}
                                                    @endif                
                                                    </td>
                                                    <td style="text-align:right;">
                                                    @if(strpos($row->total_price, '.000') !== false)
                                                    {{ number_format($row->total_price, 0, ',', '.') }}
                                                    @else
                                                    {{ number_format($row->total_price, 3, ',', '.') }}
                                                    @endif                
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
        
    });
</script>
@endsection