@extends('layouts.app')

@section('header-content')
    <div class="flex w-full md:w-10/12 mx-auto flex-wrap py-4 border-b">
        <div class="flex-1">
            <h3 class="font-bold text-base md:text-2xl text-center md:text-left"> <span class="mr-3"><i class="fa fa-file-text" aria-hidden="true"></i></span> Acciones de personal</h3>
        </div>
        <div class="flex-1 hidden md:block">
            @if (session('message'))
                <x-alert :type="session('type')" :message="session('message')"></x-alert>
            @endif
        </div>
    </div>
@endsection
@section('content')
    <action-index-component
        :rpending="'/gte/actions/pendings'"
        :rapproved="'/gte/actions/approved'"
        :rnotapproved="'/gte/actions/notapproved'"
        :approve="'/gte/actions/approve/'"
        :notapprove="'/gte/actions/notapprove/'"
        :roleuser="'{{Auth::user()->role_id}}'"
    ></action-index-component>
@endsection