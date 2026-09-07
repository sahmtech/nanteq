<?php

namespace App\Exceptions;

use Exception;

class GeneralException extends Exception
{
    public function render()
    {
        return response()->json([
            'status' => false,
            'message' => $this->getMessage()
        ], $this->getCode());
    }
}