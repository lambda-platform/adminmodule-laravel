<?php

namespace Lambda\Puzzle;

trait ErrorHandler
{
    public static function handleError($e)
    {
        dd('error');
        $dbCode = trim($e->getCode());

        switch ($dbCode) {
            case 23000:
                $errorMessage = 'my 2300 error message ';
                break;
            default:
                $errorMessage = 'database invalid';
        }
    }
}
