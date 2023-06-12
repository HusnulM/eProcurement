@extends('layouts/App')

@section('title', 'Checklist Kendaraan')

@section('additional-css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style type="text/css">
        .select2-container {
            display: block
        }

        .select2-container .select2-selection--single {
            height: 36px;
        }

        .input-error{
            outline: 1px solid red;
        }
    </style>
@endsection

@section('content')        
<div class="container-fluid">
    <form action="{{ url('checklistkendaraan/save') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Input Checklist Kendaraan</h3>
                        <div class="card-tools">
                            <button type="submit" class="btn btn-primary btn-sm btn-add-dept">
                                <i class="fas fa-save"></i> Simpan Checklist Kendaraan
                            </button>
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
                                            <input type="date" name="tanggal_cek" class="form-control" required>
                                        </td>
                                        <td>
                                            Jenis Kendaraan
                                        </td>
                                        <td>
                                            <input type="text" name="jenis_kendaraan" id="jenis_kendaraan" class="form-control" required readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            Nama Driver
                                        </td>
                                        <td>
                                            <input type="text" name="nama_driver" class="form-control" required>
                                        </td>
                                        <td>
                                            Nomor Rangka / Mesin
                                        </td>
                                        <td>
                                            <input type="text" name="nomor_rangka" id="nomor_rangka" class="form-control" readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            Plat Kendaraan
                                        </td>
                                        <td>
                                            <select name="unitdesc" id="find-unitdesc" class="form-control"></select>
                                            <!-- <input type="text" name="hari_tanggal" class="form-control"> -->
                                        </td>
                                        <td>
                                            Bahan Bakar
                                        </td>
                                        <td>
                                            <input type="text" name="bahan_bakar" id="bahan_bakar" class="form-control" readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            Tahun
                                        </td>
                                        <td>
                                            <input type="text" name="tahun" id="tahun" class="form-control" readonly>
                                        </td>
                                        <td>
                                            Odometer
                                        </td>
                                        <td>
                                            <input type="text" name="odometer" id="odometer" class="form-control" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Attachment
                                        </td>
                                        <td>
                                            <input type="file" class="form-control" name="efile[]" multiple="multiple">
                                        </td>
                                        <td>
                                            
                                        </td>
                                        <td>
                                            
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
                                                            <tr>
                                                                <td>1</td>
                                                                <td>
                                                                    SIM DRIVER
                                                                    <input type="hidden" name="ckl_grp1_name[]" value="SIM DRIVER">
                                                                </td>
                                                                <td>
                                                                    <select name="status_adm[]" class="form-control">
                                                                        <option value="">---</option>
                                                                        <option value="ADA">ADA</option>
                                                                        <option value="TIDAK">TIDAK</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon3">Masa Berlaku</span>
                                                                        </div>
                                                                        <input type="date" name="masa_berlaku[]" class="form-control">
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <select name="jenis_sim[]" class="form-control">
                                                                        <option value="">---</option>
                                                                        <option value="B1 Umum">B1 Umum</option>
                                                                        <option value="B2 Umum">B2 Umum</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <!-- STNK -->
                                                            <tr>
                                                                <td>2</td>
                                                                <td>
                                                                    STNK
                                                                    <input type="hidden" name="ckl_grp1_name[]" value="STNK">
                                                                </td>
                                                                <td>
                                                                    <select name="status_adm[]" class="form-control">
                                                                        <option value="">---</option>
                                                                        <option value="ADA">ADA</option>
                                                                        <option value="TIDAK">TIDAK</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon3">Masa Berlaku</span>
                                                                        </div>
                                                                        <input type="date" name="masa_berlaku[]" class="form-control">
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    
                                                                </td>
                                                            </tr>
                                                            <!-- PAJAK STNK -->
                                                            <tr>
                                                                <td>3</td>
                                                                <td>
                                                                    PAJAK STNK
                                                                    <input type="hidden" name="ckl_grp1_name[]" value="PAJAK STNK">
                                                                </td>
                                                                <td>
                                                                    <select name="status_adm[]" class="form-control">
                                                                        <option value="">---</option>
                                                                        <option value="ADA">ADA</option>
                                                                        <option value="TIDAK">TIDAK</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon3">Masa Berlaku</span>
                                                                        </div>
                                                                        <input type="date" name="masa_berlaku[]" class="form-control">
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    
                                                                </td>
                                                            </tr>
                                                            <!-- BUKU KIR -->
                                                            <tr>
                                                                <td>4</td>
                                                                <td>
                                                                    BUKU KIR
                                                                    <input type="hidden" name="ckl_grp1_name[]" value="BUKU KIR">
                                                                </td>
                                                                <td>
                                                                    <select name="status_adm[]" class="form-control">
                                                                        <option value="">---</option>
                                                                        <option value="ADA">ADA</option>
                                                                        <option value="TIDAK">TIDAK</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon3">Masa Berlaku</span>
                                                                        </div>
                                                                        <input type="date" name="masa_berlaku[]" class="form-control">
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    
                                                                </td>
                                                            </tr>
                                                            <!-- UIN USAHA -->
                                                            <tr>
                                                                <td>5</td>
                                                                <td>
                                                                    UIN USAHA
                                                                    <input type="hidden" name="ckl_grp1_name[]" value="UIN USAHA">
                                                                </td>
                                                                <td>
                                                                    <select name="status_adm[]" class="form-control">
                                                                        <option value="">---</option>
                                                                        <option value="ADA">ADA</option>
                                                                        <option value="TIDAK">TIDAK</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon3">Masa Berlaku</span>
                                                                        </div>
                                                                        <input type="date" name="masa_berlaku[]" class="form-control">
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    
                                                                </td>
                                                            </tr>
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
                                                                    {{ $row->checklist_name }}
                                                                    <input type="hidden" name="ckl_grp2_name[]" value="{{ $row->checklist_name }}">
                                                                </td>
                                                                <td>
                                                                    <select name="grp2_ada_tidak[]" class="form-control">
                                                                        <option value="">---</option>
                                                                        <option value="ADA">ADA</option>
                                                                        <option value="TIDAK">TIDAK</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="quantity[]" class="form-control inputNumber" id="qty{{ $key+1 }}" onkeypress="return isNumberKey(event)">
                                                                </td>
                                                                <td>
                                                                    <select name="grp2_baik_rusak[]" class="form-control">
                                                                        <option value="">---</option>
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
                                                                    {{ $row->checklist_name }}
                                                                    <input type="hidden" name="ckl_grp3_name[]" value="{{ $row->checklist_name }}">
                                                                </td>
                                                                <td>
                                                                    <select name="grp3_ada_tidak[]" class="form-control">
                                                                        <option value="">---</option>
                                                                        <option value="CUKUP">CUKUP</option>
                                                                        <option value="KURANG">KURANG</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select name="grp3_baik_rusak[]" class="form-control">
                                                                        <option value="">---</option>
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
                                                                    {{ $row->checklist_name }}
                                                                    <input type="hidden" name="ckl_grp4_name[]" value="{{ $row->checklist_name }}">
                                                                </td>
                                                                <td>
                                                                    <select name="grp4_ada_tidak[]" class="form-control">
                                                                        <option value="">---</option>
                                                                        <option value="ADA">ADA</option>
                                                                        <option value="TIDAK">TIDAK</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select name="grp4_baik_rusak[]" class="form-control">
                                                                        <option value="">---</option>
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
                                                                        <option value="">---</option>
                                                                        <option value="LAYAK">LAYAK</option>
                                                                        <option value="TIDAK LAYAK">TIDAK LAYAK</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2</td>
                                                                <td>Catatan Hasil Pemeriksaan</td>
                                                                <td>
                                                                    <textarea name="remark" cols="30" rows="10" class="form-control"></textarea>
                                                                </td>
                                                            </tr>
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
    </form>
</div>
@endsection

@section('additional-modal')

@endsection

@section('additional-js')
<script src="{{ asset('/assets/js/select2.min.js') }}"></script>
<script>    
    function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            // if (charCode > 31 && (charCode < 48 || charCode > 57))
            if (charCode > 31 && (charCode != 46 &&(charCode < 48 || charCode > 57)))
                return false;
            return true;
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
            placeholder: 'Masukkan Plat Kendaraan',
            width: '100%',
            minimumInputLength: 0,
            ajax: {
                url: base_url + '/master/kendaraan/findkendaraan2',
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
                                id: item.no_kendaraan + ' - ' + item.model_kendaraan,
                                // id: item.no_kendaraan,
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
            $('#jenis_kendaraan').val(data[0].model_kendaraan);
            $('#nomor_rangka').val(data[0].no_rangka);
            $('#bahan_bakar').val(data[0].bahan_bakar);
            $('#tahun').val(data[0].tahun);
            $('#odometer').val(data[0].odometer);
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

        $('.inputNumber').on('keypress', function(){
            // alert(this.id)
            // var inputQty = formatNumber(this.value);
            // $('#'+this.id).val('');

            // console.log(inputQty)

            // setInputFilter(document.getElementById('#'+this.id), function(value) {
            // return /^-?\d*[.,]?\d{0,2}$/.test(value); }, "Must be a currency value");
        });

        

        function setInputFilter(textbox, inputFilter, errMsg) {
            ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop", "focusout"].forEach(function(event) {
                textbox.addEventListener(event, function(e) {
                if (inputFilter(this.value)) {
                    // Accepted value
                    if (["keydown","mousedown","focusout"].indexOf(e.type) >= 0){
                    this.classList.remove("input-error");
                    this.setCustomValidity("");
                    }
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    // Rejected value - restore the previous one
                    this.classList.add("input-error");
                    this.setCustomValidity(errMsg);
                    this.reportValidity();
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                } else {
                    // Rejected value - nothing to restore
                    this.value = "";
                }
                });
            });
        }

        

    });
</script>
@endsection