<?php

namespace StellarSecurity\SupportClient\Facades;

use Illuminate\Support\Facades\Facade;

class Support extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \StellarSecurity\SupportClient\Support::class;
    }
}
