<?php declare(strict_types=1);

namespace Kiboko\Component\Promise;

use Kiboko\Component\Promise\Resolution\Failure;
use Kiboko\Component\Promise\Resolution\Pending;
use Kiboko\Component\Promise\Resolution\Success;
use Kiboko\Contract\Promise\DeferredInterface;
use Kiboko\Contract\Promise\PromiseInterface;
use Kiboko\Contract\Promise\Resolution\FailureInterface;
use Kiboko\Contract\Promise\Resolution\ResolutionInterface;
use Kiboko\Contract\Promise\Resolution\SuccessInterface;
use Kiboko\Contract\Promise\ResolvablePromiseInterface;

/**
 * @api
 */
final class Promise implements ResolvablePromiseInterface
{
    /** @var callable */
    private $successCallbacks;
    /** @var callable */
    private $failureCallbacks;
    private ResolutionInterface $resolution;

    public function __construct()
    {
        $this->successCallbacks = [];
        $this->failureCallbacks = [];
        $this->resolution = new Pending();
    }

    public function defer(): DeferredInterface
    {
        return new Deferred($this);
    }

    public function then(callable $callback): PromiseInterface
    {
        if ($this->resolution instanceof SuccessInterface) {
            $callback($this->resolution->value());
        }

        $this->successCallbacks[] = $callback;

        return $this;
    }

    public function failure(callable $callback): PromiseInterface
    {
        if ($this->resolution instanceof FailureInterface) {
            $callback($this->resolution->error());
        }

        $this->failureCallbacks[] = $callback;

        return $this;
    }

    public function resolve($value): void
    {
        if (!$this->resolution instanceof Pending) {
            throw new AlreadyResolvedPromise('The promise was already resolved, cannot resolve again.');
        }

        $this->resolution = new Success($value);
        foreach ($this->successCallbacks as $callback) {
            try {
                $callback($value);
            } catch (\Throwable $e) {
                throw new \RuntimeException('A promise handler should not throw exceptions.', 0, $e);
            }
        }
    }

    public function fail(\Throwable $failure): void
    {
        if (!$this->resolution instanceof Pending) {
            throw new AlreadyResolvedPromise('The promise was already resolved, cannot resolve again.');
        }

        $this->resolution = new Failure($failure);
        foreach ($this->failureCallbacks as $callback) {
            try {
                $callback($failure);
            } catch (\Throwable $e) {
                throw new \RuntimeException('A promise handler should not throw exceptions.', 0, $e);
            }
        }
    }

    public function isResolved(): bool
    {
        return !$this->resolution instanceof Pending;
    }

    public function isSuccess(): bool
    {
        return $this->resolution instanceof SuccessInterface;
    }

    public function isFailure(): bool
    {
        return $this->resolution instanceof FailureInterface;
    }

    public function resolution(): ResolutionInterface
    {
        return $this->resolution;
    }
}
