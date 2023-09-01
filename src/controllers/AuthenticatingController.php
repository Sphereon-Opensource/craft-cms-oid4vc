<?php

namespace sphereon\craft\controllers;

use Craft;
use craft\web\Controller;

abstract class AuthenticatingController extends Controller
{
    protected function setAuthorization(?\craft\base\Model $settings, \craft\web\Request|\craft\console\Request|\yii\web\Request|\yii\console\Request $request): void
    {
        $accessToken = $settings->getStaticAccessToken();
        if ($accessToken == null || strlen($accessToken) == 0) {
            $msg = 'A static access token must be configured in the plugin configuration';
            Craft::error($msg);
            throw new \RuntimeException($msg);
        }

        $request->headers->set("Authorization", "Bearer ". $accessToken);
    }
}