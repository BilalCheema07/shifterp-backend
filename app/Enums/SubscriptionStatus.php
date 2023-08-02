<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class SubscriptionStatus extends Enum
{
    const ACTIVE =  1;
    const IN_PROCESSING =  2;
    const CANCEL = 3;
    const PAUSE = 4;
}
