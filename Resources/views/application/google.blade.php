@extends('application.layouts.app')

@section('title')
    @lang('analytics::breadcrumb_translation.analytics.admin.index')
@endsection

@push('styles')

@endpush

@push('scripts')

@endpush

@section('content')
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">
            <div class="col-xl-8">
                @widget('Modules\Analytics\Widgets\GlobalWidget',['view_id'=>$view_id])
            </div>
            <div class="col-xl-4">
                <div class="row">
                    <div class="col-xl-12">
                        @widget('Modules\Analytics\Widgets\TopSocialsMediasWidget',['view_id'=>$view_id])
                    </div>
                    <div class="col-xl-12">
                        @widget('Modules\Analytics\Widgets\TopBrowsersWidget',['view_id'=>$view_id])
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                @widget('Modules\Analytics\Widgets\UserTypesWidget',['view_id'=>$view_id])
            </div>
            <div class="col-xl-4">
                @widget('Modules\Analytics\Widgets\PrintSupportWidget',['view_id'=>$view_id])
            </div>
            <div class="col-xl-4">
                @widget('Modules\Analytics\Widgets\TopCountriesWidget',['view_id'=>$view_id])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                @widget('Modules\Analytics\Widgets\TopPagesWidget',['view_id'=>$view_id])
            </div>
        </div>
    </div>
@endsection
