<?php

namespace App\Foundation\Alert\Exceptions;

use Psr\Http\Message\ResponseInterface;

/**
 * @author wangzh
 * @date 2022-01-26
 * @package App\Foundation\Alert\Exceptions
 */
class CouldNotSendNotification extends \Exception
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @param ResponseInterface $response
     * @param string $message
     * @param int|null $code
     */
    public function __construct(ResponseInterface $response, string $message, int $code = null)
    {
        $this->response = $response;
        $this->message = $message;
        $this->code = $code ?? $response->getStatusCode();

        parent::__construct($message, $code);
    }

    /**
     * @param ResponseInterface $response
     * @return self
     */
    public static function serviceRespondedWithAnError(ResponseInterface $response)
    {
        return new self(
            $response,
            sprintf('WechatWork responded with an error: `%s`', $response->getBody()->getContents())
        );
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }
}
