<?php

namespace Authorization\Redis;

use Predis\Command\Command;

class UserPassport extends Command
{
    const Cmder = __CLASS__;

    public function getId()
    {
        return 'guard.passport';
    }
}