<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ISSUE_ACCESS_TOKEN()
 * @method static static ACCESS_API()
 */
final class TokenAbility extends Enum
{
    const ISSUE_ACCESS_TOKEN  =  'issue-access-token';
    const ACCESS_API = 'access-api';
}
