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
    private const NONE = 'none';
    private const VERIFIED_PRESENTATION = 'vp';
    private const CREDENTIAL_SUBJECT_FLATTENED = 'cs-flat';
}
