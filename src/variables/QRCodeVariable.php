<?php
namespace sphereon\craft\variables;

use sphereon\craft\SphereonOID4VC;

use Craft;

/**
 * @author    sphereon
 * @package   craft
 * @since     0.1.0
 */
class QRCodeVariable
{
    // Public Methods
    // =========================================================================

    /**
     * @param ?int $size
     * @return string
     */
    public function generate(?int $size = null): string
    {
        return SphereonOID4VC::getInstance()->siopservice->createAuthRequestQR($size);
    }
}
