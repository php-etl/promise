<?php declare(strict_types=1);

namespace Kiboko\Component\Promise\Resolution;

/**
 * @internal
 */
final class Failure implements FailureInterface
{
    /** @var \Throwable */
    private $error;

    public function __construct(\Throwable $error)
    {
        $this->error = $error;
    }

    public function error(): \Throwable
    {
        return $this->error;
    }
}
