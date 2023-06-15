<?php


namespace sphereon\craft\models;

use JsonMapper;
use Psr\Http\Message\StreamInterface;

/**
 * @author    Sphereon
 * @package   Craft
 * @since     0.1.0
 */
class GenerateAuthRequestURIResponse


{
    // Public Properties
    // =========================================================================

    public string $correlationId;
    public string $definitionId;
    public string $authRequestURI;
    public string $authStatusURI;

    /**
     * @throws \JsonMapper_Exception
     */
    public static function fromJson(StreamInterface $json): GenerateAuthRequestURIResponse
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
