<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\Response;

abstract class Response implements ResponseInterface
{
    protected $data;

    /**
     * @var bool
     */
    protected $successful;

    public function __construct($data)
    {
        $this->successful = true;
        $this->data = $data;

        if (is_array($data) && isset($data['errors'])) {
            $this->successful = false;
        } else {
            $this->init();
        }
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->successful;
    }
}
