<?php
namespace Evheniys\Turbosms\Facades;
/**
 * laravel-turbosms.
 * autor: evheniys
 * 
 */

use Illuminate\Support\Facades\Facade;

class Turbosms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'turbosms';
    }
}

