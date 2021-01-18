<?php declare(strict_types=1);

namespace spec\Kiboko\Component\Promise;

class TestBrokenInvokable
{
    private $exception;

    public function __construct($exception)
    {
        $this->exception = $exception;
    }

    public function __invoke($value)
    {
        throw $this->exception;
    }
}
