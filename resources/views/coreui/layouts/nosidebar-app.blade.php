@extends("coreui.layouts.skeleton")
    
{{-- @include("coreui.partials.sidebar") --}}
<div class="wrapper d-flex flex-column min-vh-100 bg-light">
        @include("coreui.partials.header")
        @yield("page-content")
</div>
@include("coreui.partials.footer")
    