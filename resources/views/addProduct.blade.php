@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            {{ trans('backpack::crud.add') }} <span>Products</span>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
            <li><a href="/admin/product" class="text-capitalize">Products</a></li>
            <li class="active">{{ trans('backpack::crud.add') }}</li>
        </ol>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <a href="/admin/product"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>Products</span></a><br><br>
            <form method="POST" action="/product/save" accept-charset="UTF-8">{!! csrf_field() !!}
                <div class="box">

                    <div class="box-header with-border">
                        <h3 class="box-title">Add a new  product</h3>
                    </div>
                    <div class="box-body row">
                        <!-- load the view from the application if it exists, otherwise load the one in the package -->
                        <!-- load the view from the application if it exists, otherwise load the one in the package -->
                        <!-- text input -->
                        <div class="form-group col-md-12">
                            <label>Asin ( For Bulk Add input as asin1,asin2, ... , asinN )</label>

                            <input type="text" name="asin" value="" class="form-control">


                        </div>

                    </div><!-- /.box-body -->
                    <div class="box-footer">

                        <div id="saveActions" class="form-group">

                            <input type="hidden" name="save_action" value="save_and_back">

                            <div class="btn-group">

                                <button type="submit" class="btn btn-success">
                                    <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
                                    <span data-value="save_and_back">Save and back</span>
                                </button>


                            </div>

                            <a href="/admin/product" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;Cancel</a>
                        </div>
                    </div><!-- /.box-footer-->

                </div><!-- /.box -->
            </form>

            {{--{!! Form::open(array('url' => '/product/save', 'method' => 'post')) !!}--}}
            {{--<div class="box">--}}

                {{--<div class="box-header with-border">--}}
                    {{--<h3 class="box-title">{{ trans('backpack::crud.add_a_new') }} Product</h3>--}}
                {{--</div>--}}
                {{--<div class="box-body row">--}}
                    {{--<!-- load the view from the application if it exists, otherwise load the one in the package -->--}}
                    {{--@if(view()->exists('vendor.backpack.crud.form_content'))--}}
                        {{--@include('vendor.backpack.crud.form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])--}}
                    {{--@else--}}
                        {{--@include('crud::form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])--}}
                    {{--@endif--}}
                {{--</div><!-- /.box-body -->--}}
                {{--<div class="box-footer">--}}

{{--                    @include('crud::inc.form_save_buttons')--}}

                {{--</div><!-- /.box-footer-->--}}

            {{--</div><!-- /.box -->--}}
            {{--{!! Form::close() !!}--}}
        </div>
    </div>

@endsection