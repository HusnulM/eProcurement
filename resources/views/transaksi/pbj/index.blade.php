@extends('layouts/App')

@section('title', 'Pembuatan PBJ')

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
    <form action="{{ url('transaction/pbj/save') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"></h3>
                        <div class="card-tools">
                            <button type="submit" class="btn btn-success btn-sm btn-add-dept">
                                <i class="fas fa-save"></i> Simpan PBJ
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-12">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="tglpbj">Tanggal PBJ</label>
                                            <input type="date" name="tglpbj" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="requestto">Tujuan Permintaan</label>
                                            <input type="text" name="requestto" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="kepada">Kepada</label>
                                            <!-- <input type="text" name="kepada" class="form-control"> -->
                                            <select name="kepada" id="kepada" class="form-control" required>
                                                <option value="">Pilih Department</option>
                                                @foreach($department as $key => $row)
                                                    <option value="{{ $row->department }}">{{ $row->department }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="unitdesc">Unit Desc / Code</label>
                                            <input type="text" name="unitdesc" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="engine">Engine Model</label>
                                            <input type="text" name="engine" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="chassis">Chassis S/N</label>
                                            <input type="text" name="chassis" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="refrensi">Refrensi Permintaan</label>
                                            <input type="text" name="refrensi" class="form-control">
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
                                            <input type="text" name="typeModel" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="user">User</label>
                                            <!-- <input type="text" name="user" class="form-control"> -->
                                            <select name="user" id="user" class="form-control" required>
                                                <option value="">Pilih Mekanik</option>
                                                @foreach($mekanik as $key => $row)
                                                    <option value="{{ $row->nama }}">{{ $row->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>        
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="kodeJasa">Kode Barang / Jasa</label>
                                            <input type="text" name="kodeJasa" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="nginesn">Engine S/N</label>
                                            <input type="text" name="nginesn" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="hmkm">HM / KM</label>
                                            <input type="text" name="hmkm" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="budgetcode">Budget / Cost Code</label>
                                            <input type="text" name="budgetcode" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table table-sm">
                                            <thead>
                                                <th>Part No. / Type</th>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Figure</th>
                                                <th>Remark</th>
                                                <th style="text-align:right;">
                                                    <button type="button" class="btn btn-success btn-sm btn-add-pbj-item">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </th>
                                            </thead>
                                            <tbody id="tbl-pbj-body">

                                            </tbody>
                                            <!-- <tfoot>
                                                <tr>
                                                    <td colspan="7"></td>
                                                    <td style="text-align:right;">
                                                        <button type="button" class="btn btn-success btn-sm btn-add-pbj-item">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tfoot> -->
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

@endsection

@section('additional-js')
<script src="{{ asset('/assets/js/select2.min.js') }}"></script>
<script>    
    $(document).ready(function(){
        var count = 0;

        let _token   = $('meta[name="csrf-token"]').attr('content');

        // var amount = document.getElementById('amount');

        // amount.addEventListener('keyup', function(e){
        //     amount.value = formatNumber(this.value, '');
        // });<input type="text" name="parts[]" class="form-control">

        var fCount = 0;

        $('.btn-add-pbj-item').on('click', function(){
            fCount = fCount + 1;
            $('#tbl-pbj-body').append(`
                <tr>
                    <td>
                        <select name="parts[]" id="find-part`+fCount+`" class="form-control"></select>
                    </td>
                    <td>
                        <input type="text" name="partdesc[]" id="partdesc`+fCount+`" class="form-control" readonly>
                    </td>
                    <td>
                        <input type="text" name="quantity[]" class="form-control" onkeypress="`+validate(event)+`">
                    </td>
                    <td>
                        <input type="text" name="uoms[]" id="partunit`+fCount+`" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="figures[]" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="remarks[]" class="form-control">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btnRemove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);

            $('.btnRemove').on('click', function(e){
                e.preventDefault();
                $(this).closest("tr").remove();
            });

            $(document).on('select2:open', (event) => {
                const searchField = document.querySelector(
                    `.select2-search__field`,
                );
                if (searchField) {
                    searchField.focus();
                }
            });

            $('#find-part'+fCount).select2({ 
                placeholder: 'Type Part Number',
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
                                    text: item.material + ' - ' +item.matdesc,
                                    slug: item.partnumber,
                                    id: item.material,
                                    ...item
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $('#find-part'+fCount).on('change', function(){
                // alert(this.value)
                
                var data = $('#find-part'+fCount).select2('data')
                console.log(data);

                // alert(data[0].material);
                $('#partdesc'+fCount).val(data[0].partname);
                $('#partunit'+fCount).val(data[0].matunit);
            });
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
            this.value = formatRupiah(this.value,'');
        });

        function formatRupiah(angka, prefix){
            var number_string = angka.toString().replace(/[^.\d]/g, '').toString(),
            split   		  = number_string.split('.'),
            sisa     		  = split[0].length % 3,
            rupiah     		  = split[0].substr(0, sisa),
            ribuan     		  = split[0].substr(sisa).match(/\d{3}/gi);
        
            if(ribuan){
                separator = sisa ? ',' : '';
                rupiah += separator + ribuan.join(',');
            }
        
            rupiah = split[1] != undefined ? rupiah + '.' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');            
        }
    });
</script>
@endsection