<?php

namespace App\Enums;

enum Gender: string
{
    case Male = 'male';

    case Female = 'female';

    public static function labels(): array
    {
        return [
            self::Male->value => 'Male',
            self::Female->value => 'Female',
        ];
    }

    public static function instance(string $value): self
    {
        return match ($value) {
            self::Male->value => self::Male,
            self::Female->value => self::Female,
            default => throw new \InvalidArgumentException("Invalid value for Gender: {$value}"),
        };
    }
}