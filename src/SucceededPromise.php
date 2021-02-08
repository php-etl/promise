<?php declare(strict_types=1);

namespace Kiboko\Component\Promise;

use Kiboko\Component\Promise\Resolution;
use Kiboko\Contract\Promise\DeferredInterface;
use Kiboko\Contract\Promise\PromiseInterface;
use Kiboko\Contract\Promise\Resolution\ResolutionInterface;
use Kiboko\Contract\Promise\Resolution\SuccessInterface;

/**
 * @api
 */
final class SucceededPromise implements PromiseInterface
{
    private SuccessInterface $resolution;

    public function __construct($value)
    {
        $this->resolution = new Resolution\Success($value);
    }

    public function then(callable $callback): PromiseInterface
    {
        $callback($this->resolution->value());
        return $this;
    }

    public function failure(callable $callback): PromiseInterface
    {
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
        return true;
    }

    public function isFailure(): bool
    {
        return false;
    }

    public function resolution(): ResolutionInterface
    {
        return $this->resolution;
    }
}
