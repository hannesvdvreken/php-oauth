<?php
namespace OAuth\Support;

use Illuminate\Support\Facades\Facade as IlluminateFacade;

class Facade extends IlluminateFacade
{
    /**
     * Getting the key for resolving from the container.
     * 
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'oauth';
    }
}