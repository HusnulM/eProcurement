@extends('layouts/App')

@section('title', 'Change Cost Master')

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
    <form action="{{ url('/master/costmaster/updatecost') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Change Cost Master</h3>
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
                                <label for="costCode">Cost Code</label>
                                <input type="text" name="costCode" class="form-control" value="{{ $costmaster->cost_code }}" required>
                                <input type="hidden" name="costcodeid" value="{{ $costmaster->id }}">
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label for="costCode">Cost Name</label>
                                <input type="text" name="costName" class="form-control" value="{{ $costmaster->cost_desc }}" required>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label for="costCode">Cost Group</label>
                                <select name="costGroup" id="costGroup" class="form-control" required>
                                    <option value="{{ $scgroup->cost_group }}">{{ $scgroup->cost_group }} {{ $scgroup->cost_group_desc }}</option>
                                    @foreach($costgroup as $key => $row)
                                        <option value="{{ $row->cost_group }}">{{ $row->cost_group }} - {{ $row->cost_group_desc }}</option>
                                    @endforeach
                                </select>
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

    });
</script>
@endsection
