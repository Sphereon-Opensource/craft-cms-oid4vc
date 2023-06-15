<?php
namespace sphereon\craft\services;

use craft\base\Component;
use craft\helpers\Template;
use Endroid\QrCode\QrCode;
use Twig\Markup;

/**
 * @author    Sphereon
 * @package   Craft
 * @since     0.1.0
 */
class QRCodeService extends Component
{
    // Public Methods
    // =========================================================================

    /*
     * @param mixed $data
     * @param ?int $size
     * @return Markup
     */
    public function generate(string $data, ?int $size = null): Markup
    {
        $generator = new QrCode($data);
        if ($size) {
            $generator->setSize($size);
        }

        return Template::raw($generator->writeDataUri());
    }
}
