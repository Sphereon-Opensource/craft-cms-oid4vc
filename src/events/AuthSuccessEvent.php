<?php
/**
 * @link https://sphereon.com/
 * @copyright Copyright (c) Sphereon.com Ltd
 * @license Apache-2.0
 */


namespace sphereon\craft\events;

/**
 * Class AuthSuccessEvent
 *
 * @author    Sphereon
 * @since     0.1
 */
class AuthSuccessEvent extends AuthStatusEvent
{
    // Properties
    // =========================================================================

    public array $verifiedData;
}
