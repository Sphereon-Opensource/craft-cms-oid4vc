<?php

namespace sphereon\craft\controllers;

use Craft;
use craft\web\Controller;
use craft\web\Response;
use GuzzleHttp\Exception\GuzzleException;
use sphereon\craft\models\GenerateAuthRequestURIResponse;
use sphereon\craft\SphereonOID4VC;
use yii\db\Exception;
use yii\web\BadRequestHttpException;

class SiopController extends Controller
{


    protected array|int|bool $allowAnonymous = [
        'init',
        'status',

    ];

    public function beforeAction($action): bool
    {

        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * @throws \JsonMapper_Exception
     * @throws GuzzleException
     * @throws Exception
     */
    public function actionInit(): \yii\web\Response
    {
        $authRequestURI = SphereonOID4VC::getInstance()->siopservice->createAuthRequest();
        if (!($authRequestURI instanceof GenerateAuthRequestURIResponse)) {
            Craft::warning(sprintf('Generate QR code problem. Body: %s', json_encode($authRequestURI)), 'qr');
            throw new \RuntimeException('Could not generate QR code');
        }
        $qr = SphereonOID4VC::getInstance()->qrservice->generate($authRequestURI->authRequestURI);
        $resp = $authRequestURI;
        $resp->qr = $qr;
        return $this->asJson($resp);
    }


    /**
     * @throws BadRequestHttpException
     */
    public function actionStatus(): \yii\web\Response
    {
        $this->requirePostRequest();
        $settings = SphereonOID4VC::getInstance()->getSettings();
        $request = Craft::$app->request;
        $correlationId = $request->post('correlationId');
        $definitionId = $request->post('definitionId', $settings->getPresentationDefinitionId());
        $authStatus = SphereonOID4VC::getInstance()->siopservice->getAuthStatus($correlationId, $definitionId);
        if ($authStatus->status === 'verified') {
            $verifiedData = SphereonOID4VC::getInstance()->siopservice->mapVerifiedData($authStatus);
            if ($verifiedData instanceof Response) {
                return $verifiedData;
            }
            $authStatus->verifiedData = $verifiedData;
            /*$this->setSuccessFlash($authStatus->);
            return $this->redirect($settings->getAuthSuccessRedirectUrl());*/
        }
        return $this->asJson($authStatus);
    }


    private function getCurrentUser()
    {
        return Craft::$app->getUser()->getIdentity();
    }
}