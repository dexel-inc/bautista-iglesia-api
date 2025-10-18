<?php

namespace App\Constants;

enum TypesPrayletter: string
{
    case LINK  = "link";
    case FILE  = "file";

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
