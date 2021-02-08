<?php declare(strict_types=1);

namespace Kiboko\Component\Promise;

use Kiboko\Component\Promise\Resolution\Failure;
use Kiboko\Contract\Promise\DeferredInterface;
use Kiboko\Contract\Promise\PromiseInterface;
use Kiboko\Contract\Promise\Resolution\FailureInterface;
use Kiboko\Contract\Promise\Resolution\ResolutionInterface;

/**
 * @api
 */
final class FailedPromise implements PromiseInterface
{
    private FailureInterface $resolution;

    public function __construct(\Throwable $exception)
    {
        $this->resolution = new Failure($exception);
    }

    public function then(callable $callback): PromiseInterface
    {
        return $this;
    }

    public function failure(callable $callback): PromiseInterface
    {
        $callback($this->resolution->error());
        return $this;
    }

    public function defer(): DeferredInterface
    {
        return new Deferred($this);
    }

    public function isResolved(): bool
    {
        return true;
    }

    public function isSuccess(): bool
    {
        return false;
    }

    public function isFailure(): bool
    {
        return true;
    }

    public function resolution(): ResolutionInterface
    {
        return $this->resolution;
    }
}
