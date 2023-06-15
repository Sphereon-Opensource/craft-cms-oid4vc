<?php

namespace sphereon\craft\services;

use craft\base\Component;
use sphereon\craft\models\PluginSettings;
use sphereon\craft\SphereonOID4VC;

class SettingsService extends Component
{
    // Public Methods
    // =========================================================================

    public function get(): PluginSettings
    {
        return PluginSettings::initFromSettings(SphereonOID4VC::getInstance()->settings);
    }
}