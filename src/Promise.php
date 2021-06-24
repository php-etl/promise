<?php declare(strict_types=1);

namespace Kiboko\Component\Promise;

use Kiboko\Contract\Promise as Contract;

/**
 * @api
 */
final class Promise implements Contract\ResolvablePromiseInterface
{
    /** @var callable */
    private $successCallbacks;
    /** @var callable */
    private $failureCallbacks;
    private Contract\Resolution\ResolutionInterface $resolution;

    public function __construct()
    {
        $this->successCallbacks = [];
        $this->failureCallbacks = [];
        $this->resolution = new Resolution\Pending();
    }

    public function defer(): Contract\DeferredInterface
    {
        return new Deferred($this);
    }

    public function then(callable $callback): Contract\PromiseInterface
    {
        if ($this->resolution instanceof Contract\Resolution\SuccessInterface) {
            $this->resolution->apply($callback);
        }

        $this->successCallbacks[] = $callback;

        return $this;
    }

    public function failure(callable $callback): Contract\PromiseInterface
    {
        if ($this->resolution instanceof Contract\Resolution\FailureInterface) {
            $this->resolution->apply($callback);
        }

        $this->failureCallbacks[] = $callback;

        return $this;
    }

    public function resolve($value): void
    {
        if (!$this->resolution instanceof Resolution\Pending) {
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

    public function fail(\Throwable $failure): void
    {
        if (!$this->resolution instanceof Resolution\Pending) {
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

    public function resolution(): Contract\Resolution\ResolutionInterface
    {
        return $this->resolution;
    }
}
