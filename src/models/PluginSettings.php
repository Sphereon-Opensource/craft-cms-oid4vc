<?php


namespace sphereon\craft\models;

/**
 * @author    Sphereon
 * @package   Craft
 * @since     0.1.0
 */
class PluginSettings


{
    // Public Properties
    // =========================================================================

    private Settings $default;

    /**
     * @param \sphereon\craft\models\Settings $settings
     */
    private function __construct(\sphereon\craft\models\Settings $settings)
    {
        $this->default = $settings;
    }


    public static function init(): PluginSettings
    {
        return new PluginSettings(new Settings());
    }

    /**
     * @param \sphereon\craft\models\Settings $settings
     */
    public static function initFromSettings(\sphereon\craft\models\Settings $settings): PluginSettings
    {
        return new PluginSettings($settings);
    }

    /**
     * @return \sphereon\craft\models\Settings
     */
    public function getDefault(): \sphereon\craft\models\Settings
    {
        return $this->default;
    }

}
