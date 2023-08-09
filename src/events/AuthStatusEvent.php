<?php
/**
 * @link https://sphereon.com/
 * @copyright Copyright (c) Sphereon.com Ltd
 * @license Apache-2.0
 */


namespace sphereon\craft\events;

use craft\base\Event;
use sphereon\craft\models\AuthStatusResponse;

/**
 * Class GenerateAuthRequestEvent
 *
 * @author    Sphereon
 * @since     0.1
 */
class AuthStatusEvent extends Event
{
    // Properties
    // =========================================================================


    public string $correlationId;

    public string $definitionId;

    public AuthStatusResponse $authStatusResponse;
}
