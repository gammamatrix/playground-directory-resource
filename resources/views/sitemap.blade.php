<?php
$user = \Illuminate\Support\Facades\Auth::user();

$viewContacts = \Playground\Auth\Facades\Can::access($user, [
    'allow' => false,
    'any' => true,
    'privilege' => 'playground-directory-resource:contact:viewAny',
    'roles' => ['admin', 'manager', 'publisher'],
])->allowed();

$viewLocations = \Playground\Auth\Facades\Can::access($user, [
    'allow' => false,
    'any' => true,
    'privilege' => 'playground-directory-resource:location:viewAny',
    'roles' => ['admin', 'manager', 'publisher'],
])->allowed();


if (!$viewContacts && !$viewLocations) {
    return;
}
?>
<div class="card my-1">
    <div class="card-body">
        <h2>Directory</h2>

        <div class="row">
            <div class="col-sm-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        Directory Contact
                        <small class="text-muted">contacts and locations</small>
                    </div>
                    <ul class="list-group list-group-flush">
                        @if ($viewContacts)
                            <a
                                href="{{ route('playground.directory.resource.contacts') }}"
                                class="list-group-item list-group-item-action"
                            >
                                Contacts
                            </a>
                        @endif

                        @if ($viewLocations)
                            <a
                                href="{{ route('playground.directory.resource.locations') }}"
                                class="list-group-item list-group-item-action"
                            >
                                Locations
                            </a>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
