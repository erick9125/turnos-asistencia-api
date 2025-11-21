<?php

namespace App\Exceptions;

use Exception;

/**
 * Excepción lanzada cuando se intenta eliminar un turno que tiene marcas asociadas
 */
class ShiftHasMarksException extends Exception
{
    public function __construct(string $message = 'No se puede eliminar un turno que tiene marcas asociadas.')
    {
        parent::__construct($message, 400);
    }
}
