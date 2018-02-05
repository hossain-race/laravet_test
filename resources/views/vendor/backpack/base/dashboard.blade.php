@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        {{ trans('backpack::base.dashboard') }}<small>{{ trans('backpack::base.first_page_you_see') }}</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url(config('backpack.base.route_prefix', 'admin')) }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">{{ trans('backpack::base.dashboard') }}</li>
      </ol>
    </section>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-4 col-sm-8 col-xs-16">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{\App\Models\Product::count()}}</h3>

                    <p>Total Products</p>
                </div>
                <div class="icon">
                    <i class="fa fa-amazon"></i>
                </div>
                <a href="{{ url('admin/product') }}" class="small-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
            </div>

        </div>

        <div class="col-md-4 col-sm-8 col-xs-16">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{\App\Models\Product::where('selling_qty','>',1)->count()}}</h3>

                    <p>Total Hijaced Products</p>
                </div>
                <div class="icon">
                    <i class="fa fa-warning"></i>
                </div>
                <a href="{{ url('admin/hijackercheck') }}" class="small-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
            </div>

        </div>
        <!-- /.col -->

        <div class="col-md-4 col-sm-8 col-xs-16">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{\App\Models\Product::where('selling_qty','=',1)->count()}}</h3>

                    <p>Total Safe Products</p>
                </div>
                <div class="icon">
                    <i class="fa fa-thumbs-o-up"></i>
                </div>
                <a href="#" class="small-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
            </div>

        </div>
    </div>
@endsection
