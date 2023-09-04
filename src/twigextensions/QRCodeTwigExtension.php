<?php
namespace sphereon\craft\twigextensions;

use sphereon\craft\SphereonOID4VC;

use Craft;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * @author    webdna
 * @package   QRCode
 * @since     0.0.1
 */
class QRCodeTwigExtension extends AbstractExtension
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'QRCode';
    }

    /**
     * @inheritdoc
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('qrcode', [$this, 'generate']),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('qrcode', [$this, 'generate']),
        ];
    }

    /**
     * @param ?string $definitionId
     * @param ?int $size
     * @return string
     */
    public function generate(?string $definitionId = null, ?int $size = null): string
    {
        return SphereonOID4VC::$plugin->siopservice->createAuthRequestQR($definitionId, $size);
    }
}
