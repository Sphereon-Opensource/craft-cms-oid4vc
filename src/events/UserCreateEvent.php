<?php
/**
 * @link https://sphereon.com/
 * @copyright Copyright (c) Sphereon.com
 * @license Apache-2.0
 */

namespace sphereon\craft\events;

use craft\base\Event;
use craft\elements\User;

/**
 * Class UserCreateEvent
 *
 * @author    Sphereon
 * @since     0.1
 */
class UserCreateEvent extends Event
{
    // Properties
    // =========================================================================

    /**
     * @var User
     */
    public $user;

    /**
     * @var string
     */
    public $issuer;
}
