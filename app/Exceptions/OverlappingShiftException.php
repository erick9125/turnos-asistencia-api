<?php

namespace App\Exceptions;

use Exception;

/**
 * Excepción lanzada cuando se intenta crear o actualizar un turno que se solapa con otro
 */
class OverlappingShiftException extends Exception
{
    public function __construct(string $message = 'El turno se solapa con otro turno existente del mismo trabajador.')
    {
        parent::__construct($message, 400);
    }
}
