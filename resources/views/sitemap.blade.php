<?php
$user = \Illuminate\Support\Facades\Auth::user();

$viewLocations = \Playground\Auth\Facades\Can::access($user, [
    'allow' => false,
    'any' => true,
    'privilege' => 'playground-directory-resource:location:viewAny',
    'roles' => ['admin', 'manager', 'publisher'],
])->allowed();

$viewSublocations = \Playground\Auth\Facades\Can::access($user, [
    'allow' => false,
    'any' => true,
    'privilege' => 'playground-directory-resource:sublocation:viewAny',
    'roles' => ['admin', 'manager', 'publisher'],
])->allowed();


if (!$viewLocations && !$viewSublocations) {
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
                    Directory Index
                    <small class="text-muted">locations and sublocations</small>
                    </div>
                    <ul class="list-group list-group-flush">

                        @if ($viewLocations)
                        <a href="{{ route('playground.directory.resource.locations') }}" class="list-group-item list-group-item-action">
                            Locations
                        </a>
                        @endif

                        @if ($viewSublocations)
                        <a href="{{ route('playground.directory.resource.sublocations') }}" class="list-group-item list-group-item-action">
                            Sublocations
                        </a>
                        @endif

                    </ul>
                </div>
            </div>

        </div>

    </div>
</div>
