@extends('errors.sb-admin')

@section('title', __('Too Many Requests'))
@section('image', asset('assets/img/illustrations/504-error-gateway-timeout.svg'))
@section('message', __('Too many requests. Please wait and try again later.'))
