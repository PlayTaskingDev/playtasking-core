<?php

namespace App\Enums;

use Kongulov\Traits\InteractWithEnum;

enum CodeTypeEnum: string {
    use InteractWithEnum;

    case UNIQUE = 'unique';
    case MULTIPLE = 'multiple';
    case UNIQUE_EXTERNAL = 'unique_external';
}