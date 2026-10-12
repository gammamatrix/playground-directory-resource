@extends (
    "playground::layouts.resource.form",
    [
        "withFormInfo" => "playground-directory-resource::contact/form-info",
        "withFormAccess" => true,
    ]
)

@section ("form-quaternary")
    @includeWhen (
        ! empty($_method) && "patch" === $_method,
        "playground-directory-resource::contact/form-revisions"
    )
@endsection
