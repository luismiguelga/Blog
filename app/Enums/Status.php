<?php

namespace App\Enums;

enum Status: string
{
    case DRAFT = 'Borrador';
    case PUBLIC = 'Publico';
    case PRIVATE = 'Privado';

    public function getLabel()
    {
        return match ($this) {
            Status::DRAFT => 'Borrador',
            Status::PUBLIC => 'Publico',
            Status::PRIVATE => 'Privado'
        };
    }

    public function getColor():string
    {
        return match ($this) {
            self::DRAFT => 'info',
            self::PUBLIC => 'success',
            self::PRIVATE => 'primary'
        };
    }
}
