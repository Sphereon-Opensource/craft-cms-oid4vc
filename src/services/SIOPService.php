<?php

namespace sphereon\craft\services;

use Craft;
use craft\base\Component;
use craft\web\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use http\Exception\RuntimeException;
use sphereon\craft\events\AuthStatusEvent;
use sphereon\craft\events\AuthSuccessEvent;
use sphereon\craft\events\GenerateAuthRequestEvent;
use sphereon\craft\models\AuthStatusResponse;
use sphereon\craft\models\GenerateAuthRequestURIResponse;
use sphereon\craft\SphereonOID4VC;
use stdClass;
use Twig\Markup;
use yii\db\Exception;

class SIOPService extends Component
{

    public const EVENT_BEFORE_SIOPV2_AUTH = 'beforeSIOPv2Auth';
    public const EVENT_PENDING_SIOPV2_AUTH = 'pendingSIOPv2Auth';
    public const EVENT_AFTER_SIOPV2_AUTH = 'afterSIOPv2Auth';
    private string $agentBaseUrl;


    private string $presentationDefinitionId;
    private Client $client;

    public function __construct()
    {
        $settings = SphereonOID4VC::getInstance()->getSettings();
        $this->agentBaseUrl = $settings->getAgentBaseUrl();
        $this->presentationDefinitionId = $settings->getPresentationDefinitionId();
        $this->initialize();
        parent::__construct();
    }


    public function getAuthRequestBaseUrl()
    {
        // todo: Probably nice to make this configurable
        return sprintf('%s/webapp/definitions/%s', $this->getAgentBaseUrl(), $this->getPresentationDefinitionId());
    }

    public function getCreateAuthRequestUrl()
    {
        // todo: Probably nice to make this configurable
        return sprintf('%s/auth-requests', $this->getAuthRequestBaseUrl());
    }


    public function getAuthStatusUrl()
    {
        // todo: Probably nice to make this configurable
        return sprintf('%s/webapp/auth-status', $this->getAgentBaseUrl());
    }


    /**
     * @return string
     */
    public function getAgentBaseUrl()
    {
        return $this->agentBaseUrl;
    }

    /**
     * @return string
     */
    public function getPresentationDefinitionId()
    {
        return $this->presentationDefinitionId;
    }

    public function initialize(): void
    {
        $this->client = Craft::createGuzzleClient();
    }

    /**
     * @throws \JsonMapper_Exception
     * @throws Exception
     * @throws GuzzleException
     */
    public function createAuthRequest(): GenerateAuthRequestURIResponse|Response
    {

        // POST request
        $response = $this->client->request('POST', $this->getCreateAuthRequestUrl(), [
            RequestOptions::HEADERS => [
                'Content-Type' => 'application/json',
            ],

        ]);
        if ($response->getStatusCode() < 300) {
            return GenerateAuthRequestURIResponse::fromJson($response->getBody());
        }
        Craft::warning(sprintf('Create Auth Request error: %s', $response->getBody()));
        return Craft::$app->response->setStatusCode(400);
    }


    /**
     * @throws \JsonMapper_Exception
     * @throws GuzzleException
     * @throws Exception
     */
    public function createAuthRequestQR(?int $size = null): Markup
    {
        $authRequest = $this->createAuthRequest();
        $qr = SphereonOID4VC::getInstance()->qrservice->generate($authRequest->authRequestURI, $size);
        if ($this->hasEventHandlers(self::EVENT_BEFORE_SIOPV2_AUTH)) {
            $event = new GenerateAuthRequestEvent(['authRequestUri' => $authRequest, 'qr' => $qr]);
            $this->trigger(self::EVENT_BEFORE_SIOPV2_AUTH, $event);
        }
        return $qr;
    }


    /**
     * @throws \JsonMapper_Exception
     * @throws Exception
     * @throws GuzzleException
     */
    public function getAuthStatus(string $correlationId, string $definitionId): AuthStatusResponse|Response
    {
        // POST request
        $body = new stdClass();
        $body->correlationId = $correlationId;
        $body->definitionId = $definitionId;

        $response = $this->client->request('POST', $this->getAuthStatusUrl(), [
            RequestOptions::BODY => json_encode($body),
            RequestOptions::HEADERS => [
                'Content-Type' => 'application/json',
            ],

        ]);
        if ($response->getStatusCode() < 300) {
            $authStatusResponse = AuthStatusResponse::fromJson($response->getBody());
            if (!($authStatusResponse instanceof AuthStatusResponse)) {
                Craft::warning(sprintf('Authentication status problem. Body: %s', json_encode($authStatusResponse)), 'siopv2');
                throw new \RuntimeException('Could not authenticate');
            }
            $verified = $authStatusResponse->status === 'verified' && $authStatusResponse->payload !== null;

            if ($this->hasEventHandlers(self::EVENT_PENDING_SIOPV2_AUTH)) {
                $event = new AuthStatusEvent(['correlationId' => $correlationId, 'definitionId' => $definitionId, 'authStatusResponse' => $authStatusResponse]);
                $this->trigger(self::EVENT_PENDING_SIOPV2_AUTH, $event);
            }
            if ($this->hasEventHandlers(self::EVENT_AFTER_SIOPV2_AUTH) && $verified) {
                // Please note that the above 'PENDING' also triggered for this one, as it would allow a listener to provide some custom logic on there end, instead of relying on this event
                $verifiedData = $this->mapVerifiedData($authStatusResponse);
                if ($verifiedData instanceof Response) {
                    return $verifiedData;
                }
                $event = new AuthSuccessEvent(['correlationId' => $correlationId, 'definitionId' => $definitionId, 'authStatusResponse' => $authStatusResponse, 'verifiedData' => $verifiedData]);
                $this->trigger(self::EVENT_PENDING_SIOPV2_AUTH, $event);
            }
            return $authStatusResponse;
        }
        Craft::warning(sprintf('Auth Status response error: %d, %s', $response->getStatusCode(), $response->getBody()));
        return Craft::$app->response->setStatusCode(400);
    }

    public function mapVerifiedData(AuthStatusResponse $authStatusResponse): array|Response
    {
        if (!$authStatusResponse || $authStatusResponse->status !== 'verified') {
            Craft::warning(sprintf('Auth Status response cannot be converted to verified data, if the status is not "verified", %s', json_encode($authStatusResponse)));
            return Craft::$app->response->setStatusCode(400);
        }
        // todo: Add mapping function with twig template support to go from VP to the individual items

        return array(
            'firstName' => 'TODO firstname',
            'lastName' => 'TODO lastname',
            'email' => 'TODO email'
        );
    }
}