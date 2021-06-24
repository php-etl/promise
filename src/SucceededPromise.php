<?php declare(strict_types=1);

namespace Kiboko\Component\Promise;

use Kiboko\Contract\Promise as Contract;

/**
 * @api
 */
final class SucceededPromise implements Contract\PromiseInterface
{
    private Contract\Resolution\SuccessInterface $resolution;

    public function __construct($value)
    {
        $this->resolution = new Resolution\Success($value);
    }

    public function then(callable $callback): Contract\PromiseInterface
    {
        $this->resolution->apply($callback);
        return $this;
    }

    public function failure(callable $callback): Contract\PromiseInterface
    {
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
        return true;
    }

    public function isFailure(): bool
    {
        return false;
    }

    public function resolution(): Contract\Resolution\ResolutionInterface
    {
        return $this->resolution;
    }
}
