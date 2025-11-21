<?php

namespace App\Exceptions;

use Exception;

/**
 * Excepción lanzada cuando se intenta usar un dispositivo inactivo
 */
class InactiveDeviceException extends Exception
{
    public function __construct(string $message = 'No se puede usar un dispositivo inactivo.')
    {
        parent::__construct($message, 400);
    }
}
