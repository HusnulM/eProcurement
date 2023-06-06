@extends('layouts/App')

@section('title', 'Create BAST')

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
    <form action="{{ url('logistic/bast/save') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create BAST</h3>
                        <div class="card-tools">
                            <button type="submit" class="btn btn-primary btn-sm btn-add-dept">
                                <i class="fas fa-save"></i> Save BAST
                            </button>
                            <a href="{{ url('/logistic/bast') }}" class="btn btn-default btn-sm">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2 col-md-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="nomorbast">Nomor BAST</label>
                                            <input type="text" name="nomorbast" class="form-control" required>
                                            <input type="hidden" name="pbjID" value="{{ $pbjheader->id }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="tglreq">Tanggal BAST</label>
                                            <input type="date" name="tglbast" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="vendor">Nama Pemberi</label>
                                            <input type="text" name="requestor" class="form-control" value="{{ Auth::user()->name }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="penerima">Nama Penerima</label>
                                            <select name="penerima" id="find-penerima" class="form-control"></select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="kepada">Department</label>
                                            <input type="text" class="form-control" name="department" value="{{ $pbjheader->department }}" readonly>
                                        </div>
                                    </div>
                                    
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
                                        <table id="tbl-po-item" class="table table-bordered table-hover table-striped table-sm">
                                            <thead>
                                                <th>Part Number</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>PBJ Number</th>
                                            </thead>
                                            <tbody id="tbl-pbj-body">
                                            @foreach($pbjitems as $key => $row)
                                                <tr>
                                                    <td>
                                                        {{ $row->partnumber }} - {{ $row->description }}
                                                        <input type="hidden" name="material[]" value="{{ $row->partnumber }}">
                                                        <input type="hidden" name="matdesc[]" value="{{ $row->description }}">
                                                    </td>
                                                    <td style="text-align:right;">
                                                        {{ number_format($row->quantity,0) }}
                                                        <input type="hidden" name="quantity[]" value="{{ $row->quantity }}">
                                                    </td>
                                                    <td>
                                                        {{ $row->unit }}
                                                        <input type="hidden" name="unit[]" value="{{ $row->unit }}">
                                                    </td>
                                                    <td>
                                                        {{ $row->pbjnumber }}
                                                        <input type="hidden" name="pbjnumber[]" value="{{ $row->pbjnumber }}">
                                                        <input type="hidden" name="pbjitem[]" value="{{ $row->pbjitem }}">
                                                        <input type="hidden" name="wonum[]" value="{{ $row->wonum }}">
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
                            <th>Part Number / Material</th>
                            <th>Description</th>
                            {{-- <th>Part Number</th> --}}
                            {{-- <th>Warehouse</th>
                            <th>Warehouse Name</th>
                            <th>Available Quantity</th> --}}
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
@endsection

@section('additional-js')
<script src="{{ asset('/assets/js/select2.min.js') }}"></script>
<script>    
    $(document).ready(function(){
        var count = 0;
        let selected_pr_items = [];
        let selected_items    = [];
        let _token   = $('meta[name="csrf-token"]').attr('content');

        var fCount = 0;

        $(document).on('select2:open', (event) => {
            const searchField = document.querySelector(
                `.select2-search__field`,
            );
            if (searchField) {
                searchField.focus();
            }
        });
        $('#find-penerima').select2({ 
            placeholder: 'Nama',
            width: '100%',
            minimumInputLength: 0,
            ajax: {
                url: base_url + '/logistic/bast/finduser',
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
                                text: item.name,
                                slug: item.name,
                                id: item.id,
                                ...item
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $('#find-penerima').on('change', function(){
            // alert(this.value)
            
            var data = $('#find-penerima').select2('data')
            console.log(data);
        });
    });
</script>
@endsection