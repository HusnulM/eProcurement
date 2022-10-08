@extends('layouts/App')

@section('title', 'Dashboard')

@section('additional-css')
<style>
    a:hover, a:visited, a:link, a:active
    {
        text-decoration: none;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    @foreach(userMenu() as $headMenu)
        @if($headMenu->menugroup == null)
            @foreach(userSubMenu() as $detailMenu)
                @if($headMenu->menugroup === $detailMenu->menugroup)
                    <div class="col-12 col-sm-6 col-md-3">
                        <a href="{{ url($detailMenu->route) }}" style="text-decoration: none;">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">{{ $detailMenu->menu_desc }}</span>
                            </div>
                        </div>
                        </a>
                    </div>
                @endif
            @endforeach
        @else
            <div class="card">
                <div class="card-header">
                    <h3>{{ $headMenu->groupname }} <i class="{{ $headMenu->groupicon ? $headMenu->groupicon : 'fas fa-tachometer-alt' }}"></i></h3>                    
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach(userSubMenu() as $detailMenu)
                            @if($headMenu->menugroup === $detailMenu->menugroup)
                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 mb-2">
                                <a href="{{ url($detailMenu->route) }}">
                                    <div class="card-body bg-info" style="border-top-right-radius: 10px; border-top-left-radius: 10px;">
                                        <!-- <div class="inner" style="margin-left:10px;">
                                        </div> -->
                                        <div class="icon" style="text-align:center;">
                                            @if($detailMenu->icon)
                                            <img src="/assets/img/icon/{{ $detailMenu->icon}}" class="img-fluid center-block" alt="Icons" style="width: 150px; height: 150px;">
                                            @else
                                            <img src="/assets/img/icon/Report.png" class="img-fluid center-block" alt="Icons" style="width: 150px; height: 150px;">
                                            @endif
                                        </div>
                                    </div>
                                </a>
                                <div class="card-footer bg-info" style="border-bottom-right-radius: 10px; border-bottom-left-radius: 10px;">
                                    <!-- <hr style="border: 1px solid white;"> -->
                                    <a href="{{ url($detailMenu->route) }}" class="small-box-footer" style="text-align:left;"> 
                                        <i class="fas fa-arrow-circle-right"></i> {{ $detailMenu->menu_desc }}
                                    </a>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>            
        @endif
    @endforeach
</div>
@endsection