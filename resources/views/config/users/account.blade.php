@extends('layouts/App')

@section('title', 'Update User Account')

@section('additional-css')
@endsection

@section('content')        
<div class="container">
    <form action="{{ url('/user/account') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">User Information</h3>
                        <div class="card-tools">
                            <button type="submit" class="btn btn-success btn-sm btn-add-dept">
                                <i class="fas fa-save"></i> Update User Account
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="name">Namer</label>
                                            <input type="text" name="name" id="name" class="form-control" value="{{ Auth::user()->name }}" required>
                                            <input type="hidden" name="iduser" id="iduser" class="form-control" value="{{ Auth::user()->id }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="username">User ID / Username</label>
                                            <input type="text" name="username" id="username" class="form-control" value="{{ Auth::user()->username }}" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" id="email" class="form-control" value="{{ Auth::user()->email }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" name="password" id="password" class="form-control" autocomplete="new-password" >
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="esignfile">e-signature</label>
                                            <input type="file" name="esignfile" id="esignfile" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 text-center">
                                        <p><b>e-Signature</b></p>
                                        <img src="{{ asset(Auth::user()->s_signfile ?? '/assets/dist/img/no-image.png') }}" class="img-thumbnail" alt="E-sign" style="width:200px; height:200px;">
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
<script>
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
    $(document).ready(function(){
        // var amount = document.getElementById('amount');

        // amount.addEventListener('keyup', function(e){
        //     amount.value = formatNumber(this.value, '');
        // });
        
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