<?php

namespace App\Enums\Tenant;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OrderStatus extends Enum
{
    const NEW =   1;
    const REMOTE =  2;
    const READY_TO_GO = 3;
    const NOTE =   4;
    const COMPLETED =   5;
    const NOT_ENOUGH_INGREDIENTS = 6;
}
