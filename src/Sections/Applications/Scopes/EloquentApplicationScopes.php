<?php

namespace AwemaPL\Allegro\Sections\Applications\Scopes;

use AwemaPL\Repository\Scopes\ScopesAbstract;

class EloquentApplicationScopes extends ScopesAbstract
{
    protected $scopes = [
        'q' =>SearchApplication::class,
    ];
}
