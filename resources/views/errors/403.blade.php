@extends('errors.sb-admin')

@section('title', __('Forbidden'))
@section('image', asset('assets/img/illustrations/403-error-forbidden.svg'))
@section('message', __($exception->getMessage() ?: 'Your user level does not have permission to access this resource.'))
