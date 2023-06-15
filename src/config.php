<?php

/**
 * OpenID4VP and SIOPv2 Auth plugin for Craft CMS
 *
 * Enable authentication to Craft through the use of SIOPv2 (with support for OID4VP)
 *
 * @link      https://sphereon.com
 * @copyright Copyright (c) 2023 Sphereon.com
 */

/**
 * OID4VP SIOPv2 config.php
 *
 * This file exists only as a template for the Sphereon SIOPv2 OID4VP settings.
 * It does nothing on its own.
 *
 * Don't edit this file, instead copy it to 'craft/config' as 'sphereon-siopv2-oid4vp.php'
 * and make your changes there to override default settings.
 *
 * Once copied to 'craft/config', this file will be multi-environment aware as
 * well, so you can have different settings groups for each environment, just as
 * you do for 'general.php'
 */

return [
    // Whether to automatically create a user for verified authentications that don't match an account
    "autoCreateUser" => false,

    // OID4VP Presentation Definition ID
    "presentationDefinitionId" => 'sphereon',

    // SSI Agent base URL (public demo agent)
    'agentBaseUrl' => 'https://ssi-backend.sphereon.com',

    // The page to redirect to once auth is successful
    'authSuccessRedirectUrl' => '/dbc-conference-success'
];