@extends('layouts/App')

@section('title', 'Edit Item Master')

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
    <form action="{{ url('/master/item/update') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Item Master</h3>
                        <div class="card-tools">
                            <a href="{{ url('/master/item') }}" class="btn btn-default btn-sm">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-save"></i> SAVE
                            </button>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <div class="row">

                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="partnumber">Item Code</label>
                                            <input type="text" name="partnumber" id="partnumber" class="form-control" value="{{ $materialdata->partnumber }}" readonly required autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="partname">Item Name</label>
                                            <input type="text" name="partname" id="partname" class="form-control" value="{{ $materialdata->partname }}" required autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="matspec">Spek / Type / Ukuran</label>
                                            <input type="text" name="matspec" id="matspec" class="form-control" autocomplete="off" value="{{ $materialdata->matspec }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="itemtype">Item Category</label>
                                            <!-- <input type="text" name="itemtype" id="itemtype" class="form-control" autocomplete="off" required> -->
                                            <select name="itemtype" id="itemtype" class="form-control">
                                                <option value="{{ $materialdata->mattype }}">{{ $materialdata->mattypedesc }}</option>
                                                @foreach($matcat as $key => $row)
                                                <option value="{{ $row->id }}">{{ $row->mattypedesc }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="itemunit">Item Base Unit</label>
                                            <select name="itemunit" id="itemunit" class="form-control">
                                                <option value="{{ $materialdata->matunit }}">{{ $materialdata->matunit }}</option>
                                                @foreach($matuom as $key => $row)
                                                <option value="{{ $row->uom }}">{{ $row->uom }} - {{ $row->uomdesc }}</option>
                                                @endforeach
                                            </select>
                                            <!-- <input type="text" name="itemunit" id="itemunit" class="form-control" autocomplete="off" required> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <table class="table table-stripped table-sm">
                                    <thead>
                                        <th>Conversion Unit</th>
                                        <th>Conversion Value</th>
                                        <th>Base Unit</th>
                                        <th>Base Value</th>
                                        <th style="text-align:center;">
                                            <button type="button" class="btn btn-success btn-sm" id="btn-add-conv-uom">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </th>
                                    </thead>
                                    <tbody id="tbl-conv-uom-body">
                                        @foreach($materialuom as $key => $dt)
                                        <tr>
                                            <td>
                                                <select name="convuom[]" class="form-control convuom" required>
                                                    <option value="{{ $dt->altuom }}">{{ $dt->altuom }}</option>
                                                    @foreach($matuom as $key => $row)
                                                        <option value="{{ $row->uom }}">{{ $row->uom }} - {{ $row->uomdesc }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="convalue[]" value="{{ $dt->convalt }}" class="form-control" />
                                            </td>
                                            <td>
                                                <input type="text" name="baseuom[]" class="form-control" value="{{ $dt->baseuom }}" readonly/>
                                            </td>
                                            <td>
                                                <input type="number" name="baseuomval[]" value="{{ $dt->convbase }}" class="form-control" />
                                            </td>
                                            <td style="text-align:center;">
                                                <button type="button" class="btn btn-danger btn-sm btnRemove">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <a href="{{ url('/master/item') }}" class="btn btn-default btn-sm">
                                    <i class="fa fa-arrow-left"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-save"></i> SAVE
                                </button>
                            </div>
                        </div>                             -->
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('additional-js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        $('#btn-add-conv-uom').on('click', function(){
            var baseUom = $('#itemunit').val();

            $('#tbl-conv-uom-body').append(`
                <tr>
                    <td>
                        <select name="convuom[]" class="form-control convuom" required>
                            <option value="">Pilih Conversi Uom</option>
                            @foreach($matuom as $key => $row)
                                <option value="{{ $row->uom }}">{{ $row->uom }} - {{ $row->uomdesc }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="convalue[]" class="form-control" />
                    </td>
                    <td>
                        <input type="text" name="baseuom[]" class="form-control" value="`+ baseUom +`" readonly/>
                    </td>
                    <td>
                        <input type="number" name="baseuomval[]" class="form-control" />
                    </td>
                    <td style="text-align:center;">
                        <button type="button" class="btn btn-danger btn-sm btnRemove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);

            $('.convuom').select2();

            $('.btnRemove').on('click', function(e){
                e.preventDefault();
                $(this).closest("tr").remove();
            });
        });

        $('.btnRemove').on('click', function(e){
            e.preventDefault();
            $(this).closest("tr").remove();
        });

        $('#itemunit, #itemtype').select2();
    });
</script>
@endsection
