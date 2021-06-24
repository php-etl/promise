<?php declare(strict_types=1);

namespace Kiboko\Component\Promise;

use Kiboko\Contract\Promise as Contract;

/**
 * @api
 */
final class FailedPromise implements Contract\PromiseInterface
{
    private Contract\Resolution\FailureInterface $resolution;

    public function __construct(\Throwable $exception)
    {
        $this->resolution = new Resolution\Failure($exception);
    }

    public function then(callable $callback): Contract\PromiseInterface
    {
        return $this;
    }

    public function failure(callable $callback): Contract\PromiseInterface
    {
        $this->resolution->apply($callback);
        return $this;
    }

    public function defer(): Contract\DeferredInterface
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

    public function resolution(): Contract\Resolution\ResolutionInterface
    {
        return $this->resolution;
    }
}
