<?php declare(strict_types=1);

namespace Kiboko\Component\ETL\Promise;

class Promise implements PromiseInterface
{
    /** @var callable */
    private $successCallbacks;
    /** @var callable */
    private $failureCallbacks;
    /** @var bool */
    private $isResolved;

    public function __construct()
    {
        $this->successCallbacks = [];
        $this->failureCallbacks = [];
    }

    public function defer(): DeferredInterface
    {
        return new Deferred($this);
    }

    public function then(callable $callback): PromiseInterface
    {
        $this->successCallbacks[] = $callback;

        return $this;
    }

    public function failure(callable $callback): PromiseInterface
    {
        $this->failureCallbacks[] = $callback;

        return $this;
    }

    public function resolve($value): void
    {
        if ($this->isResolved === true) {
            throw new AlreadyResolvedPromise('The promise was already resolved, cannot resolve again.');
        }

        foreach ($this->successCallbacks as $callback) {
            $callback($value);
        }
        $this->isResolved = true;
    }

    public function fail(\Throwable $failure): void
    {
        if ($this->isResolved === true) {
            throw new AlreadyResolvedPromise('The promise was already resolved, cannot resolve again.');
        }

        foreach ($this->failureCallbacks as $callback) {
            $callback($failure);
        }
        $this->isResolved = true;
    }

    public function isResolved(): bool
    {
        return $this->isResolved;
    }
}