@extends('playground::layouts.resource.form', [
    'withFormInfo' => 'playground-directory-resource::location/form-info',
    'withFormStatus' => 'playground-directory-resource::location/form-status',
])

@section('form-tertiary')
@include('playground-directory-resource::location/form-publishing')
@endsection

@section('form-quaternary')
@includeWhen(
    !empty($_method) && 'patch' === $_method,
    'playground-directory-resource::location/form-revisions'
)
@endsection
