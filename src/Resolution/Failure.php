<?php declare(strict_types=1);

namespace Kiboko\Component\Promise\Resolution;

use Kiboko\Contract\Promise\Resolution\FailureInterface;

/**
 * @internal
 */
final class Failure implements FailureInterface
{
    public function __construct(private \Throwable $error)
    {
    }

    public function error(): \Throwable
    {
        return $this->error;
    }

    public function apply(callable $callback): void
    {
        if (($error = $callback($this->error)) !== null) {
            $this->error = $error;
        }
    }
}
