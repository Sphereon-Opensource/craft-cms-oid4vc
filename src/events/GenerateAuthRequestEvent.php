<?php
/**
 * @link https://sphereon.com/
 * @copyright Copyright (c) Sphereon.com Ltd
 * @license Apache-2.0
 */


namespace sphereon\craft\events;

use craft\base\Event;
use sphereon\craft\models\GenerateAuthRequestURIResponse;

/**
 * Class GenerateAuthRequestEvent
 *
 * @author    Sphereon
 * @since     0.1
 */
class GenerateAuthRequestEvent extends Event
{
    // Properties
    // =========================================================================


    public string|null $qr;

    public GenerateAuthRequestURIResponse $authRequestUri;
}
