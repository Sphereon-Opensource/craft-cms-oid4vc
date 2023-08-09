<?php

namespace sphereon\craft\assetbundles\qrcodefieldfield;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Sphereon
 * @package   craft
 * @since     0.0.1
 */
class QRCodeFieldFieldAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        $this->sourcePath = "@sphereon/craft/assetbundles/qrcodefieldfield/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/QRCodeField.js',
        ];

        $this->css = [
            'css/QRCodeField.css',
        ];

        parent::init();
    }
}
