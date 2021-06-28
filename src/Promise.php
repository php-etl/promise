<?php declare(strict_types=1);

namespace Kiboko\Component\Promise;

use Kiboko\Contract\Promise as Contract;

/**
 * @api
 * @template ExpectationType
 * @template ExceptionType of \Throwable
 * @implements Contract\ResolvablePromiseInterface<ExpectationType, ExceptionType>
 */
final class Promise implements Contract\ResolvablePromiseInterface
{
    /** @var array<callable(ExpectationType): void> */
    private $successCallbacks;
    /** @var array<callable(ExceptionType): void> */
    private $failureCallbacks;
    /** @var Resolution\Pending|Resolution\Success<ExpectationType>|Resolution\Failure<ExceptionType> */
    private Contract\Resolution\ResolutionInterface $resolution;

    public function __construct()
    {
        $this->successCallbacks = [];
        $this->failureCallbacks = [];
        $this->resolution = new Resolution\Pending();
    }

    /**
     * @param callable(ExpectationType): void $callback
     *
     * @return Contract\PromiseInterface<ExpectationType, ExceptionType>
     */
    public function then(callable $callback): Contract\PromiseInterface
    {
        if ($this->resolution instanceof Contract\Resolution\SuccessInterface) {
            $this->resolution->apply($callback);
        }
        if (!$this->resolution instanceof Contract\Resolution\ResolvedInterface) {
            $this->successCallbacks[] = $callback;
        }

        return $this;
    }

    /**
     * @param callable(ExceptionType): void $callback
     *
     * @return Contract\PromiseInterface<ExpectationType, ExceptionType>
     */
    public function failure(callable $callback): Contract\PromiseInterface
    {
        if ($this->resolution instanceof Contract\Resolution\FailureInterface) {
            $this->resolution->apply($callback);
        }
        if (!$this->resolution instanceof Contract\Resolution\ResolvedInterface) {
            $this->failureCallbacks[] = $callback;
        }

        return $this;
    }

    /** @return Contract\DeferredInterface<ExpectationType, ExceptionType> */
    public function defer(): Contract\DeferredInterface
    {
        /** @var Deferred<ExpectationType, ExceptionType> $deferred */
        $deferred = new Deferred($this);
        return $deferred;
    }

    /** @param ExpectationType $value */
    public function resolve($value): void
    {
        if ($this->resolution instanceof Contract\Resolution\ResolvedInterface) {
            throw new AlreadyResolvedPromise('The promise was already resolved, cannot resolve again.');
        }

        $this->resolution = new Resolution\Success($value);
        foreach ($this->successCallbacks as $callback) {
            try {
                $this->resolution->apply($callback);
            } catch (\Throwable $e) {
                throw new \RuntimeException('A promise handler should not throw exceptions.', 0, $e);
            }
        }
    }

    /** @param ExceptionType $failure */
    public function fail(\Throwable $failure): void
    {
        if ($this->resolution instanceof Contract\Resolution\ResolvedInterface) {
            throw new AlreadyResolvedPromise('The promise was already resolved, cannot resolve again.');
        }

        $this->resolution = new Resolution\Failure($failure);
        foreach ($this->failureCallbacks as $callback) {
            try {
                $this->resolution->apply($callback);
            } catch (\Throwable $e) {
                throw new \RuntimeException('A promise handler should not throw exceptions.', 0, $e);
            }
        }
    }

    public function isResolved(): bool
    {
        return !$this->resolution instanceof Resolution\Pending;
    }

    public function isSuccess(): bool
    {
        return $this->resolution instanceof Contract\Resolution\SuccessInterface;
    }

    public function isFailure(): bool
    {
        return $this->resolution instanceof Contract\Resolution\FailureInterface;
    }

    /** @return Resolution\Pending|Resolution\Success<ExpectationType>|Resolution\Failure<ExceptionType> */
    public function resolution(): Contract\Resolution\ResolutionInterface
    {
        return $this->resolution;
    }
}
