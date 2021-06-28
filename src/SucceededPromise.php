<?php declare(strict_types=1);

namespace Kiboko\Component\Promise;

use Kiboko\Contract\Promise as Contract;

/**
 * @api
 * @template ExpectationType
 * @template ExceptionType of \Throwable
 * @implements Contract\PromiseInterface<ExpectationType, ExceptionType>
 */
final class SucceededPromise implements Contract\PromiseInterface
{
    /** @var Contract\Resolution\SuccessInterface<ExpectationType> */
    private Contract\Resolution\SuccessInterface $resolution;

    /** @param ExpectationType $value */
    public function __construct($value)
    {
        $this->resolution = new Resolution\Success($value);
    }

    /**
     * @param callable(ExpectationType): void $callback
     *
     * @return Contract\PromiseInterface<ExpectationType, ExceptionType>
     */
    public function then(callable $callback): Contract\PromiseInterface
    {
        $this->resolution->apply($callback);
        return $this;
    }

    /**
     * @param callable(ExceptionType): void $callback
     *
     * @return Contract\PromiseInterface<ExpectationType, ExceptionType>
     */
    public function failure(callable $callback): Contract\PromiseInterface
    {
        return $this;
    }

    /** @return Contract\DeferredInterface<ExpectationType, ExceptionType> */
    public function defer(): Contract\DeferredInterface
    {
        /** @var Deferred<ExpectationType, ExceptionType> $deferred */
        $deferred = new Deferred($this);
        return $deferred;
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

    /** @return Contract\Resolution\SuccessInterface<ExpectationType> */
    public function resolution(): Contract\Resolution\ResolutionInterface
    {
        return $this->resolution;
    }
}
