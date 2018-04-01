@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
             <span>Added Product Report</span>
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
            @php
                $invalidAsin = array();
                $alreadyAddedAsin = array();
                $successfullyAddedAsin = array();
                foreach($products as $product)
                    if($product['status'] == 0)
                        $invalidAsin[] = $product['asin'];
                    elseif($product['status'] == 1)
                        $successfullyAddedAsin[] = 'ASIN: '.$product['asin'].'<br /> Name: '.$product['data']['name'].'<br />';
                    elseif($product['status'] == 2)
                        $alreadyAddedAsin[] = $product['asin'];
            @endphp

            @if(count($invalidAsin)>0)
                <div class="callout callout-danger">
                    <h4>Invalid ASIN ! ({!! count($invalidAsin) !!})</h4>

                    <p>{!! implode(', ', $invalidAsin) !!}</p>
                </div>
            @endif

            @if(count($alreadyAddedAsin)>0)
                <div class="callout callout-info">
                    <h4>Already Added ({!! count($alreadyAddedAsin) !!})</h4>

                    <p>{!! implode(', ', $alreadyAddedAsin) !!}</p>
                </div>
            @endif

            @if(count($successfullyAddedAsin)>0)
                <div class="callout callout-success">
                    <h4>Successfully Added ({!! count($successfullyAddedAsin) !!})</h4>

                    <p>{!! implode('<br /> ', $successfullyAddedAsin) !!}</p>
                </div>
            @endif

            {{--@php--}}
                {{--dd($products);--}}
            {{--@endphp--}}
        </div>
    </div>

@endsection