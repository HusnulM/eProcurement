@extends('layouts/App')

@section('title', 'Detail Purchase Order')

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
    <form id="form-submit-data" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Purchase Order <b>[{{ $pohdr->ponum }}]</b></h3>
                        <div class="card-tools">
                            <a href="{{ url('/po/list') }}" class='btn btn-default btn-sm btn-update-pr'>
                                <i class='fa fa-arrow-left'></i> Back
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
                                            <input type="date" name="tglreq" class="form-control" value="{{ $pohdr->podat }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="deldate">Delivery Date</label>
                                            <input type="date" name="deldate" class="form-control" value="{{ $pohdr->delivery_date }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="requestor">Type PO</label>
                                            <select name="prtype" id="prtype" class="form-control" required>
                                                @if($pohdr->potype === 'AA')
                                                    <option value="AA">Head Office</option>
                                                    <option value="SQ">Proyek</option>
                                                @else
                                                    <option value="SQ">Proyek</option>
                                                    <option value="AA">Head Office</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="vendor">Vendor</label>
                                            <select name="vendor" id="find-vendor" class="form-control" required>
                                                <option value="{{ $pohdr->vendor }}">{{ $vendor->vendor_name }}</option>
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
                                                @if($pohdr->currency === "IDR")
                                                    <option value="">IDR - Indonesian Rupiah</option>
                                                @else
                                                    <option value="USD">USD - US Dollar</option>
                                                    <option value="">IDR - Indonesian Rupiah</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="requestor">Proyek</label>
                                            <select name="project" id="proyek" class="form-control" required>
                                                <option value="{{ $sproyek->id }}">{{ $sproyek->kode_project }} - {{ $sproyek->nama_project }}</option>
                                                @foreach ($proyek as $ls => $row)
                                                    <option value="{{ $row->id }}">{{ $row->kode_project }} - {{ $row->nama_project }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="remark">Remark</label>
                                            <textarea name="remark" id="remark" cols="30" rows="4" class="form-control" placeholder="Remark...">{{ $pohdr->note }}</textarea>
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
                                                    <a class="nav-link active" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">PO Items</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-content-above-cost-tab" data-toggle="pill" href="#custom-content-above-cost" role="tab" aria-controls="custom-content-above-cost" aria-selected="false">Additional Cost</a>
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

                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="tab-content" id="custom-content-above-tabContent">
                                                    <div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
                                                        <div class="row">

                                                            <div class="col-lg-12">
                                                                <table id="tbl-po-item" class="table table-sm">
                                                                    <thead>
                                                                        <th>Item Name</th>
                                                                        <th style="text-align: right;">Quantity</th>
                                                                        <th>Unit</th>
                                                                        <th style="text-align: right;">Unit Price</th>
                                                                        <th>PR Reference</th>
                                                                        <th>Cost Code</th>
                                                                    </thead>
                                                                    <tbody id="tbl-pbj-body">
                                                                        @foreach ($poitem as $key => $row)
                                                                            <tr>
                                                                                <td>
                                                                                    {{ $row->matdesc }} {{ $row->matspec }}
                                                                                </td>
                                                                                <td style="text-align: right;">
                                                                                    {{ number_format($row->quantity,0) }}
                                                                                </td>
                                                                                <td>
                                                                                    {{ $row->unit }}
                                                                                </td>
                                                                                <td style="text-align: right;">
                                                                                    {{ number_format($row->price,0) }}
                                                                                </td>
                                                                                <td>
                                                                                    {{ $row->prnum }}
                                                                                </td>
                                                                                <td>
                                                                                    {{ $row->costcd }} - {{ $row->cost_group_desc }}
                                                                                </td>

                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane fade" id="custom-content-above-cost" role="tabpanel" aria-labelledby="custom-content-above-cost-tab">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <label for="top">Term of Payment</label>
                                                                <input type="text" class="form-control" name="termofpayment" value="{{ $pohdr->tf_top }}">
                                                            </div>
                                                            <hr>
                                                            <div class="col-lg-4 col-md-12 mb-2 mt-2">
                                                                <label for="PPn">PPN</label>
                                                                <select name="ppn" id="ppn" class="form-control form-sm">
                                                                    <option value="{{ $pohdr->ppn }}">{{ $pohdr->ppn }} %</option>
                                                                    <option value="0">0 %</option>
                                                                    <option value="11">11 %</option>
                                                                </select>
                                                            </div>
                                                            {{-- <hr> --}}
                                                            <div class="col-lg-8">
                                                                <table class="table table-sm">
                                                                    <thead>
                                                                        <th>Cost Component</th>
                                                                        <th>Cost Amount</th>
                                                                        <th style="text-align: right;">
                                                                            <button type="button" class="btn btn-success btn-sm" id="btn-add-cost">
                                                                                <i class="fa fa-add"></i>
                                                                            </button>
                                                                        </th>
                                                                    </thead>
                                                                    <tbody id="tbl-cost-body">
                                                                        @foreach ($costs as $key => $row)
                                                                            <tr>
                                                                                <td>
                                                                                    {{ $row->costname }}
                                                                                    <input type="hidden" name="costname[]" class="form-control" value="{{ $row->costname }}">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" name="costvalue[]" class="form-control" required value="{{ number_format($row->costvalue,0) }}" style="text-align: right;">
                                                                                </td>
                                                                                <td>
                                                                                    <button type="button" class="btn btn-danger btn-sm" id="btnRemoveCost`+count+`">
                                                                                        <i class="fa fa-trash"></i>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <div class="row" style="display: none;">
                                                            <div class="col-lg-12">
                                                                <input type="checkbox" id="isPOSolar" class="filled-in" {{ $pohdr->is_posolar == 'Y' ? 'checked' : '' }}/>
                                                                <label for="isPOSolar">PO Solar?</label>
                                                                <input type="hidden" name="poSolarInd" id="poSolarInd" value="0">
                                                            </div>
                                                            <div class="col-lg-12" id="inforPoSolar" style="{{ $pohdr->is_posolar == 'Y' ? '' : 'display: none;' }}">
                                                                <table class="table table-sm">
                                                                    <thead>
                                                                        <th>Cost Component</th>
                                                                        <th>Cost Amount</th>
                                                                    </thead>
                                                                    <tbody id="tbl-cost-body">
                                                                        <tr>
                                                                            <td>PBBKB ( % )</td>
                                                                            <td>
                                                                                <select name="solarpbbkb" id="solarpbbkb" class="form-control form-sm">
                                                                                    <option value="{{ $pohdr->solar_pbbkb }}">{{ $pohdr->solar_pbbkb }} %</option>
                                                                                    <option value="0">---</option>
                                                                                    <option value="7.5">7.5 %</option>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>OAT ( % )</td>
                                                                            <td>
                                                                                <input type="text" class="form-control" name="solaroat" value="{{ $pohdr->solar_oat }}">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>PPN OAT ( % )</td>
                                                                            <td>
                                                                                <select name="ppnoat" id="ppnoat" class="form-control form-sm">
                                                                                    <option value="{{ $pohdr->solar_ppn_oat }}">{{ $pohdr->solar_ppn_oat }} %</option>
                                                                                    <option value="0">---</option>
                                                                                    <option value="11">11 %</option>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
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
                                                                                    {{ formatDateTime($row->approval_date) }}
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
                                                                            <td style="width:20%;">
                                                                                <button type="button" class="btn btn-sm btn-default" onclick="previewFile('files/PO/{{$file->efile}}#toolbar=0')">
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

    });
</script>
@endsection
