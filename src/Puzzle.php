<?php

namespace Lambda\Puzzle;

use Illuminate\Support\Facades\Facade;

class Puzzle extends Facade
{
    use DBSchema;
    use ErrorHandler;

    protected static function getFacadeAccessor()
    {
        return 'Puzzle';
    }
}
