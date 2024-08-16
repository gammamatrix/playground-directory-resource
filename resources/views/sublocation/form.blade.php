@extends('playground::layouts.resource.form', [
    'withFormInfo' => 'playground-directory-resource::sublocation/form-info',
    'withFormStatus' => 'playground-directory-resource::sublocation/form-status',
])

@section('form-tertiary')
@include('playground-directory-resource::sublocation/form-publishing')
@endsection

@section('form-quaternary')
@includeWhen(
    !empty($_method) && 'patch' === $_method,
    'playground-directory-resource::sublocation/form-revisions'
)
@endsection
