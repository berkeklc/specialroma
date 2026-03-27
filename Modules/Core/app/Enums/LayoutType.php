<?php

declare(strict_types=1);

namespace Modules\Core\App\Enums;

enum LayoutType: string
{
    case Header = 'header';
    case Footer = 'footer';

    public function label(): string
    {
        return match ($this) {
            self::Header => 'Header',
            self::Footer => 'Footer',
        };
    }
}
