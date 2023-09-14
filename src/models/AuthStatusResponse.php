<?php


namespace sphereon\craft\models;

use craft\web\Response;
use JsonMapper;
use Psr\Http\Message\StreamInterface;


/**
 * @author    Sphereon
 * @package   Craft
 * @since     0.1.0
 */
class AuthStatusResponse


{
    // Public Properties
    // =========================================================================

    public string $status;
    public string $correlationId;
    public string $definitionId;
    public string|null $error;
    public int $lastUpdated;
    public AuthResponsePayload|null $payload;

    public array|null $verifiedData;


    /**
     * @throws \JsonMapper_Exception
     */
    public static function fromJson(StreamInterface $json): AuthStatusResponse
    {
        $mapper = new JsonMapper();
        return $mapper->map(json_decode($json), new self());
    }

    /**
     * @throws \JsonException
     */
    public function toJson(): false|string
    {
        return json_encode($this, JSON_THROW_ON_ERROR);
    }


}
