<?php

namespace sphereon\craft;

use Craft;
use craft\base\Event;
use yii\base\Event as YiiEvent;
use craft\events\PluginEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUserPermissionsEvent;
use craft\services\Fields;
use craft\services\Plugins;
use craft\services\UserPermissions;
use craft\web\twig\variables\CraftVariable;
use sphereon\craft\models\Settings;
use sphereon\craft\services\SettingsService;
use sphereon\craft\fields\QRCodeField as QRCodeFieldField;
use sphereon\craft\services\SIOPService;
use sphereon\craft\twigextensions\QRCodeTwigExtension;
use sphereon\craft\variables\QRCodeVariable;
use sphereon\craft\services\QRCodeService;

/**
 * @property QRCodeService $qrservice
 * @property SettingsService $settingsservice
 * @property SIOPService siopservice
 */
class SphereonOID4VC extends \craft\base\Plugin
{

    /**
     * @var SphereonOID4VC
     */
    public static $plugin;
    public bool $hasCpSettings = true;


    public function init()
    {

        parent::init();
        self::$plugin = $this;

        $this->setComponents([
            'qrservice' => QRCodeService::class,
            'settingsservice' => SettingsService::class,
            'siopservice' => SIOPService::class
        ]);

        Craft::$app->view->registerTwigExtension(new QRCodeTwigExtension());

        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = QRCodeFieldField::class;
            }
        );

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (YiiEvent $event) {
                $variable = $event->sender;
                $variable->set('qrcode', QRCodeVariable::class);
            }
        );


        // TODO: Figure out how we programmatically can set this on a guest after SIOP auth within a single session only
        Event::on(
            UserPermissions::class,
            UserPermissions::EVENT_REGISTER_PERMISSIONS,
            function(RegisterUserPermissionsEvent $event) {
                $event->permissions[] = [
                    'heading' => 'SIOPv2 and OID4VP Permissions',
                    'permissions' => [
                        'oid4vci-guest' => [
                            'label' => 'OID4VP Authenticated Guest',
                        ],
                    ],
                ];
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'app',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );

        // Custom initialization code goes here...
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel(): ?\craft\base\Model
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'sphereon-oid4vc/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}