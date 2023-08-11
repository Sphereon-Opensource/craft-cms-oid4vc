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

enum VerifiedDataMode
{
    public const NONE = 'none';
    public const VERIFIED_PRESENTATION = 'vp';
    public const CREDENTIAL_SUBJECT_FLATTENED = 'cs-flat';
}
