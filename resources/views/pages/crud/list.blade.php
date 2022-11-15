@extends("layouts.app")

@section("page-title") List Page @endsection

@section("page-style")
@endsection

@section("page-content")      
    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
        <div class="table-responsive">
            <button class="btn btn-primary" type="button" data-coreui-toggle="modal" data-coreui-target="#addFormModal">Add</button>
            <!-- @include('pages.crud.crud-table') -->
            <div class="data-list"></div>
        </div>
    </div>
@include('pages.crud.popup.add')
@include('pages.crud.popup.edit-popup')
 
@endsection
@section("page-script")   
<script src="{{url('assets/custom-assets/js/crud/crud.js')}}"></script>
@endsection