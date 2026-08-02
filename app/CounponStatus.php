<?php

namespace App;

enum CounponStatus: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Ativo',
            self::INACTIVE => 'Inativo',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'bg-emerald-500/20 text-emerald-300',
            self::INACTIVE => 'bg-red-500/20 text-red-300',
        };
    }
}
