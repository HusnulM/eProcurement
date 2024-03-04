@extends('layouts/App')

@section('title', 'Create Cost Master')

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
    <form action="{{ url('/master/costmaster/save') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create Cost Master</h3>
                        <div class="card-tools">
                            <a href="{{ url('/master/costmaster') }}" class="btn btn-default btn-sm">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-save"></i> SAVE
                            </button>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <table class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                    <thead>
                                        <th style="width: 10%;">Cost Code</th>
                                        <th>Cost Description</th>
                                        <th style="width: 25%;">Cost Group</th>
                                        <th style="text-align:center; width: 5%;">
                                            <button type="button" class="btn btn-success btn-sm btn-add-new-cost">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </th>
                                    </thead>
                                    <tbody id="tbl-cost-master-body">

                                    </tbody>
                                </table>
                            </div>
                        </div>
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
        $('.btn-add-new-cost').on('click', function(){
            $('#tbl-cost-master-body').append(`
                <tr>
                    <td>
                        <input type="text" name="costcode[]" class="form-control" required/>
                    </td>
                    <td>
                        <input type="text" name="costdesc[]" class="form-control" required/>
                    </td>
                    <td>
                        <select name="costgroup[]" class="form-control" required>
                            <option value="">Pilih cost group</option>
                            @foreach($costgroup as $key => $row)
                                <option value="{{ $row->cost_group }}">{{ $row->cost_group }} - {{ $row->cost_group_desc }}</option>
                            @endforeach
                        </select>
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

        $('#itemunit, #itemtype').select2();
    });
</script>
@endsection
