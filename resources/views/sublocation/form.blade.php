@extends(
    "playground::layouts.resource.form",
    [
        "withFormInfo" =>
            "playground-directory-resource::sublocation/form-info",
        "withFormAccess" => true,
    ]
)

@section("form-quaternary")
    @includeWhen(
        ! empty($_method) && "patch" === $_method,
        "playground-directory-resource::sublocation/form-revisions"
    )
@endsection
