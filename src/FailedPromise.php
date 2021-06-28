<?php declare(strict_types=1);

namespace Kiboko\Component\Promise;

use Kiboko\Contract\Promise as Contract;

/**
 * @api
 * @template ExpectationType
 * @template ExceptionType of \Throwable
 * @implements Contract\PromiseInterface<ExpectationType, ExceptionType>
 */
final class FailedPromise implements Contract\PromiseInterface
{
    /** @var Contract\Resolution\FailureInterface<ExceptionType> */
    private Contract\Resolution\FailureInterface $resolution;

    /** @param ExceptionType $exception */
    public function __construct(\Throwable $exception)
    {
        $this->resolution = new Resolution\Failure($exception);
    }

    /**
     * @param callable(ExpectationType): void $callback
     *
     * @return Contract\PromiseInterface<ExpectationType, ExceptionType>
     */
    public function then(callable $callback): Contract\PromiseInterface
    {
        return $this;
    }

    /**
     * @param callable(\Throwable): void $callback
     *
     * @return Contract\PromiseInterface<ExpectationType, ExceptionType>
     */
    public function failure(callable $callback): Contract\PromiseInterface
    {
        $this->resolution->apply($callback);
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
        return false;
    }

    public function isFailure(): bool
    {
        return true;
    }

    /** @return Contract\Resolution\ResolutionInterface|Contract\Resolution\ResolvedInterface<ExceptionType> */
    public function resolution(): Contract\Resolution\ResolutionInterface
    {
        return $this->resolution;
    }
}
