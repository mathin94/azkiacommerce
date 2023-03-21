<?php

return [

    'resource' => [
        'filament-resource' => AlexJustesen\FilamentSpatieLaravelActivitylog\Resources\ActivityResource::class,
        'group' => 'System',
        'sort'  => null,
    ],

    'paginate' => [5, 10, 25, 50],

];
