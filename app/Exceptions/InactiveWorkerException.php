<?php

namespace App\Exceptions;

use Exception;

/**
 * Excepción lanzada cuando se intenta realizar una operación con un trabajador inactivo
 */
class InactiveWorkerException extends Exception
{
    public function __construct(string $message = 'No se puede realizar la operación con un trabajador inactivo.')
    {
        parent::__construct($message, 400);
    }
}
