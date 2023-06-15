<?php

/**
 * Craft SIOPv2 and OID4VP plugin
 *
 * Enable authentication with SIOPv2/OID4VP
 *
 * @link      https://sphereon.com
 * @copyright Copyright (c) 2023 Sphereon.com
 */

namespace sphereon\craft\models;

use craft\base\Model;
use craft\behaviors\EnvAttributeParserBehavior;
use craft\helpers\App;

/**
 * @author    Sphereon
 * @package   Craft
 * @since     0.1.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Not implemented yet
     *
     * @var bool
     */
    public bool $autoCreateUser = false;
    public string $agentBaseUrl = '';
    public string $presentationDefinitionId = '';
    public string|null $authSuccessRedirectUrl = '';


    // Public Methods
    // =========================================================================
    public function behaviors(): array
    {
        return [
            'parser' => [
                'class' => EnvAttributeParserBehavior::class,
                'attributes' => [
                    'autoCreateUser',
                    'agentBaseUrl', 'presentationDefinitionId', 'authSuccessRedirectUrl'
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['autoCreateUser', 'boolean'],
            ['agentBaseUrl', 'string'],
            ['presentationDefinitionId', 'string'],
            ['authSuccessRedirectUrl', 'string']
        ];
    }

    public function getAutoCreateUser(): bool
    {
        return (bool)App::parseEnv($this->autoCreateUser);
    }

    public function getAgentBaseUrl(): string
    {
        return App::parseEnv($this->agentBaseUrl);
    }

    public function getPresentationDefinitionId(): string
    {
        return App::parseEnv($this->presentationDefinitionId);
    }

    public function getAuthSuccessRedirectUrl(): bool
    {
        return $this->authSuccessRedirectUrl;
    }
}
