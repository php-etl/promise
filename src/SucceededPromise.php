<?php declare(strict_types=1);

namespace Kiboko\Component\Promise;

use Kiboko\Contract\Promise as Contract;

/**
 * @api
 * @template Type
 * @implements Contract\PromiseInterface<Type>
 */
final class SucceededPromise implements Contract\PromiseInterface
{
    /** @var Contract\Resolution\SuccessInterface<Type> */
    private Contract\Resolution\SuccessInterface $resolution;

    /**
     * @param Type $value
     */
    public function __construct($value)
    {
        $this->resolution = new Resolution\Success($value);
    }

    /**
     * @param callable(Type): Type $callback
     *
     * @return Contract\PromiseInterface<Type>
     */
    public function then(callable $callback): Contract\PromiseInterface
    {
        $this->resolution->apply($callback);
        return $this;
    }

    /**
     * @param callable(\Throwable): \Throwable $callback
     *
     * @return Contract\PromiseInterface<Type>
     */
    public function failure(callable $callback): Contract\PromiseInterface
    {
        return $this;
    }

    /** @return Contract\DeferredInterface<Type> */
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
