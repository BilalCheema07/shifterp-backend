<?php

namespace App\Enums\Tenant;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OrderType extends Enum
{
    const SHIPPING =  1;
    const RECEIVING =  2;
    const PRODUCTION =  3;
    const BLEND =  4;
}
