<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\Request;

use Assert\Assert;
use Assert\InvalidArgumentException;
use Loevgaard\Linkmobility\Response\ResponseInterface;

abstract class Request implements RequestInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function validate(): void
    {
        Assert::that($this->getMethod())->choice(self::getMethods());
        Assert::that($this->getUri())->string()->startsWith('/');
        Assert::that($this->getBody())->isArray();
        Assert::that($this->getResponseClass())->implementsInterface(ResponseInterface::class);
        Assert::that($this->getOptions())->isArray();
    }

    public static function getMethods() : array
    {
        return [
            RequestInterface::METHOD_GET => RequestInterface::METHOD_GET,
            RequestInterface::METHOD_POST => RequestInterface::METHOD_POST,
            RequestInterface::METHOD_PUT => RequestInterface::METHOD_PUT,
            RequestInterface::METHOD_PATCH => RequestInterface::METHOD_PATCH,
            RequestInterface::METHOD_DELETE => RequestInterface::METHOD_DELETE,
        ];
    }

    public function getBody(): array
    {
        return [];
    }

    public function getOptions(): array
    {
        return [];
    }
}
