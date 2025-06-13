<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\Schema\TypeRegistry;
use Nuwave\Lighthouse\Schema\Types\Scalars\DateTime;

class LighthouseServiceProvider extends ServiceProvider
{
    public function boot(TypeRegistry $typeRegistry)
    {
        $typeRegistry->register(
            'DateTime',
            new DateTime()
        );
    }
}
