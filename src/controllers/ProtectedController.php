<?php

namespace sphereon\craft\controllers;

use Craft;
use craft\web\Controller;

abstract class ProtectedController extends Controller
{
    protected function isAccessTokenValid(?\craft\base\Model $settings, \craft\web\Request|\craft\console\Request|\yii\web\Request|\yii\console\Request $request): bool
    {
        $expectedToken = $settings->getStaticAccessToken();
        if ($expectedToken == null || strlen($expectedToken) == 0) {
            $msg = 'A static access token must be configured in the plugin configuration';
            Craft::error($msg);
            throw new \RuntimeException($msg);
        }
        if (strlen($expectedToken) < 3) {
            $msg = "Static access token not read, found $$expectedToken";
            Craft::error($msg);
            throw new \RuntimeException($msg);
        }

        $bearerToken = $request->headers->get("Authorization");
        if ($bearerToken == null || strlen($bearerToken) == 0) {
            $this->response->statusCode = 401;
            $this->response->content = 'The request is not contain an access token';
            return false;
        }
        $bearerToken = str_replace("Bearer ", "", $bearerToken);
        Craft::info($bearerToken);
        Craft::info($expectedToken);
        if ($bearerToken != $expectedToken) {
            $this->response->statusCode = 401;
            $this->response->content = 'The provided access token is not valid';
            return false;
        }
        return true;
    }
}