@extends('layouts/App')

@section('title', 'Detail Checklist Kendaraan')

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
    <form action="#" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Input Checklist Kendaraan</h3>
                        <div class="card-tools">
                            <a href="{{ url('/datachecklistkendaraan') }}" class="btn btn-default btn-sm">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-sm-12">
                                <table class="table table-sm">
                                    <tr>
                                        <td>
                                            Hari / Tanggal
                                        </td>
                                        <td>
                                            <input type="date" name="tanggal_cek" class="form-control" value="{{ $header->tanggal_cek }}" required>
                                        </td>
                                        <td>
                                            Jenis Kendaraan
                                        </td>
                                        <td>
                                            <input type="text" name="jenis_kendaraan" id="jenis_kendaraan" value="{{ $header->jenis_kendaraan }}" class="form-control">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            Nama Driver
                                        </td>
                                        <td>
                                            <input type="text" name="nama_driver" value="{{ $header->nama_driver }}" class="form-control">
                                        </td>
                                        <td>
                                            Nomor Rangka / Mesin
                                        </td>
                                        <td>
                                            <input type="text" name="nomor_rangka" id="nomor_rangka" value="{{ $header->nomor_rangka }}" class="form-control">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            Plat Kendaraan
                                        </td>
                                        <td>
                                            <select name="unitdesc" id="find-unitdesc" class="form-control">
                                                <option value="{{ $header->no_plat }}">{{ $header->no_plat }}</option>
                                            </select>
                                        </td>
                                        <td>
                                            Bahan Bakar
                                        </td>
                                        <td>
                                            <input type="text" name="bahan_bakar" id="bahan_bakar" value="{{ $header->bahan_bakar }}" class="form-control">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            Tahun
                                        </td>
                                        <td>
                                            <input type="text" name="tahun" id="tahun" value="{{ $header->tahun }}" class="form-control">
                                        </td>
                                        <td>
                                            Odometer
                                        </td>
                                        <td>
                                            <input type="text" name="odometer" id="odometer" value="{{ $header->odometer }}" class="form-control">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-lg-12">
                                <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">Pemeriksaan Administrasi</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-content-above-itemtype-tab" data-toggle="pill" href="#custom-content-above-itemtype" role="tab" aria-controls="custom-content-above-itemtype" aria-selected="false">Pemeriksaan Kelengkapan Kendaraan</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-content-above-itemunit-tab" data-toggle="pill" href="#custom-content-above-itemunit" role="tab" aria-controls="custom-content-above-itemunit" aria-selected="false">Pemeriksaan Kondisi Kendaraan</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-content-above-ban-tab" data-toggle="pill" href="#custom-content-above-ban" role="tab" aria-controls="custom-content-above-ban" aria-selected="false">Pemeriksaan Ban Kendaraan</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-content-above-hasil-tab" data-toggle="pill" href="#custom-content-above-hasil" role="tab" aria-controls="custom-content-above-hasil" aria-selected="false">Hasil Pemeriksaan</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-content-above-attachment-tab" data-toggle="pill" href="#custom-content-above-attachment" role="tab" aria-controls="custom-content-above-attachment" aria-selected="false">Attachment</a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="custom-content-above-tabContent">
                                    <div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title"></h3>
                                                <div class="card-tools">
                                                    <!-- <a href="{{ url('/master/item/create') }}" class="btn btn-success btn-sm">
                                                        <i class="fas fa-save"></i> Simpan Pemeriksaan Administrasi
                                                    </a> -->
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="tbl-item-master" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                                        <tbody>
                                                            @foreach($group1 as $key => $row)
                                                            <tr>
                                                                <td>{{ $key+1 }}</td>
                                                                <td>
                                                                    {{ $row->ck_administrasi }}
                                                                    <input type="hidden" name="ckl_grp1_name[]" value="{{ $row->ck_administrasi }}">
                                                                </td>
                                                                <td>
                                                                    <select name="status_adm[]" class="form-control">
                                                                        <option value="{{ $row->status_adm }}">{{ $row->status_adm ?? '---' }}</option>
                                                                        <option value="ADA">ADA</option>
                                                                        <option value="TIDAK">TIDAK</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon3">Masa Berlaku</span>
                                                                        </div>
                                                                        <input type="date" name="masa_berlaku[]" value="{{ $row->masa_berlaku }}" class="form-control">
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    @if($row->jenis_sim)
                                                                    <select name="jenis_sim[]" class="form-control">
                                                                        <option value="{{ $row->jenis_sim }}">{{ $row->jenis_sim }}</option>
                                                                        <option value="B1 Umum">B1 Umum</option>
                                                                        <option value="B2 Umum">B2 Umum</option>
                                                                    </select>
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

                                    <!-- Tab Kelengkapan Kendaraan -->
                                    <div class="tab-pane fade" id="custom-content-above-itemtype" role="tabpanel" aria-labelledby="custom-content-above-itemtype-tab">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title"></h3>
                                                <div class="card-tools">
                                                    <!-- <button type="button" class="btn btn-success btn-sm btn-create-itemcat">
                                                        <i class="fas fa-save"></i> Simpan Pemeriksaan Kelengkapan Kendaraan
                                                    </button> -->
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="tbl-item-type" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                                        <thead>
                                                            <th>No</th>
                                                            <th></th>
                                                            <th>ADA/TIDAK</th>
                                                            <th>QTY</th>
                                                            <th>BAIK/RUSAK</th>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($group2 as $key => $row)
                                                            <tr>
                                                                <td>{{ $key+1 }}</td>
                                                                <td>
                                                                    {{ $row->nama_kelengkapan }}
                                                                    <input type="hidden" name="ckl_grp2_name[]" value="{{ $row->nama_kelengkapan }}">
                                                                </td>
                                                                <td>
                                                                    <select name="grp2_ada_tidak[]" class="form-control">
                                                                        <option value="{{ $row->status_kelengkapan }}">{{ $row->status_kelengkapan }}</option>
                                                                        <option value="ADA">ADA</option>
                                                                        <option value="TIDAK">TIDAK</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="quantity[]" value="{{ $row->jumlah }}" class="form-control inputNumber">
                                                                </td>
                                                                <td>
                                                                    <select name="grp2_baik_rusak[]" class="form-control">
                                                                        <option value="{{ $row->kondisi_kelengkapan }}">{{ $row->kondisi_kelengkapan }}</option>
                                                                        <option value="BAIK">BAIK</option>
                                                                        <option value="RUSAK">RUSAK</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tab Kondisi Kendaraan -->
                                    <div class="tab-pane fade" id="custom-content-above-itemunit" role="tabpanel" aria-labelledby="custom-content-above-itemunit-tab">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title"></h3>
                                                <div class="card-tools">
                                                    <!-- <button type="button" class="btn btn-success btn-sm btn-create-itemcat">
                                                        <i class="fas fa-save"></i> Simpan Pemeriksaan Kondisi Kendaraan
                                                    </button> -->
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="tbl-item-type" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                                        <thead>
                                                            <th>No</th>
                                                            <th></th>
                                                            <th>CUKUP/KURANG</th>
                                                            <th>BAIK/RUSAK</th>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($group3 as $key => $row)
                                                            <tr>
                                                                <td>{{ $key+1 }}</td>
                                                                <td>
                                                                    {{ $row->kondisi_cek }}
                                                                    <input type="hidden" name="ckl_grp3_name[]" value="{{ $row->kondisi_cek }}">
                                                                </td>
                                                                <td>
                                                                    <select name="grp3_ada_tidak[]" class="form-control">
                                                                        <option value="{{ $row->status_kondisi }}">{{ $row->status_kondisi ?? '---' }}</option>
                                                                        <option value="CUKUP">CUKUP</option>
                                                                        <option value="KURANG">KURANG</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select name="grp3_baik_rusak[]" class="form-control">
                                                                        <option value="{{ $row->kondisi }}">{{ $row->kondisi }}</option>
                                                                        <option value="BAIK">BAIK</option>
                                                                        <option value="RUSAK">RUSAK</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tab Kondisi Ban -->
                                    <div class="tab-pane fade" id="custom-content-above-ban" role="tabpanel" aria-labelledby="custom-content-above-ban-tab">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title"></h3>
                                                <div class="card-tools">
                                                    <!-- <button type="button" class="btn btn-success btn-sm btn-create-itemcat">
                                                        <i class="fas fa-save"></i> Simpan Pemeriksaan Kondisi Ban
                                                    </button> -->
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="tbl-item-type" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                                        <thead>
                                                            <th>No</th>
                                                            <th></th>
                                                            <th>ADA/TIDAK</th>
                                                            <th>BAIK/RUSAK</th>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($group4 as $key => $row)
                                                            <tr>
                                                                <td>{{ $key+1 }}</td>
                                                                <td>
                                                                    {{ $row->nama_kondisi }}
                                                                    <input type="hidden" name="ckl_grp4_name[]" value="{{ $row->nama_kondisi }}">
                                                                </td>
                                                                <td>
                                                                    <select name="grp4_ada_tidak[]" class="form-control">
                                                                        <option value="{{ $row->status_kondisi }}">{{ $row->status_kondisi }}</option>
                                                                        <option value="ADA">ADA</option>
                                                                        <option value="TIDAK">TIDAK</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select name="grp4_baik_rusak[]" class="form-control">
                                                                        <option value="{{ $row->kondisi }}">{{ $row->kondisi }}</option>
                                                                        <option value="BAIK">BAIK</option>
                                                                        <option value="RUSAK">RUSAK</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tab Hasil Pemeriksaan -->
                                    <div class="tab-pane fade" id="custom-content-above-hasil" role="tabpanel" aria-labelledby="custom-content-above-hasil-tab">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title"></h3>
                                                <div class="card-tools">
                                                    <!-- <button type="button" class="btn btn-success btn-sm btn-create-itemcat">
                                                        <i class="fas fa-save"></i> Simpan Hasil Pemeriksaan
                                                    </button> -->
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="tbl-item-type" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                                        <thead>
                                                            <th>No</th>
                                                            <th></th>
                                                            <th>LAYAK/TIDAK LAYAK</th>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>Hasil Pemeriksaan</td>
                                                                <td>
                                                                    <select name="hasil_pemeriksaan" class="form-control" required>
                                                                        <option value="{{ $header->hasil_pemeriksaan }}">{{ $header->hasil_pemeriksaan }}</option>
                                                                        <option value="LAYAK">LAYAK</option>
                                                                        <option value="TIDAK LAYAK">TIDAK LAYAK</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Attachments -->
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
    $(document).ready(function(){
        var count = 0;

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
        $('#find-unitdesc').select2({ 
            placeholder: 'Type Unit Desc / Code',
            width: '100%',
            minimumInputLength: 0,
            ajax: {
                url: base_url + '/master/kendaraan/findkendaraan',
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
                                text: item.no_kendaraan + ' - ' + item.model_kendaraan,
                                slug: item.model_kendaraan,
                                id: item.no_kendaraan,
                                ...item
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $('#find-unitdesc').on('change', function(){
            // alert(this.value)
            
            var data = $('#find-unitdesc').select2('data')
            console.log(data);

            // alert(data[0].material);
            // $('#partdesc'+fCount).val(data[0].partname);
            // $('#partunit'+fCount).val(data[0].matunit);
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

        $('.inputNumber').on('change', function(){
            this.value = formatNumber(this.value);
        });
    });
</script>
@endsection