<?php


namespace sphereon\craft\models;

use JsonMapper;
use Psr\Http\Message\StreamInterface;


/**
 * @author    Sphereon
 * @package   Craft
 * @since     0.1.0
 */
class AuthResponsePayload


{
    // Public Properties
    // =========================================================================

    public string|null $access_token;
    public string $token_type;
    public string|null $refresh_token;
    public int $expires_in;
    public string $state;
    public string|null $id_token;
    public string $vp_token;

    /**
     * @throws \JsonMapper_Exception
     */
    public static function fromJson(StreamInterface $json): AuthResponsePayload
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
