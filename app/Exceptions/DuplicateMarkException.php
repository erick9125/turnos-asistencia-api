<?php

namespace App\Exceptions;

use Exception;

/**
 * Excepción lanzada cuando se intenta crear una marca duplicada
 */
class DuplicateMarkException extends Exception
{
    public function __construct(string $message = 'Ya existe una marca duplicada para este trabajador, sentido y minuto.')
    {
        parent::__construct($message, 400);
    }
}
