@extends('errors.sb-admin')

@section('title', __('Server Error'))
@section('image', asset('assets/img/illustrations/500-internal-server-error.svg'))
@section('message', __('The server encountered an internal error or misconfiguration and was unable to complete your request.'))
