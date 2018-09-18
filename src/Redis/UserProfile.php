<?php

namespace Authorization\Redis;

use Predis\Command\Command;

class UserProfile extends Command
{
    const Cmder = __CLASS__;
    /**
     * Returns the ID of the Redis command. By convention, command identifiers
     * must always be uppercase.
     *
     * @return string
     */
    public function getId()
    {
        return 'guard.profile';
    }
}
