<?php declare(strict_types=1);

namespace Kiboko\Component\Promise;

use Kiboko\Contract\Promise as Contract;

/**
 * @api
 * @template Type
 * @implements Contract\PromiseInterface<Type>
 */
final class FailedPromise implements Contract\PromiseInterface
{
    private Contract\Resolution\FailureInterface $resolution;

    public function __construct(\Throwable $exception)
    {
        $this->resolution = new Resolution\Failure($exception);
    }

    /**
     * @param callable(Type): Type $callback
     *
     * @return Contract\PromiseInterface<Type>
     */
    public function then(callable $callback): Contract\PromiseInterface
    {
        return $this;
    }

    /**
     * @param callable(\Throwable): \Throwable $callback
     *
     * @return Contract\PromiseInterface<Type>
     */
    public function failure(callable $callback): Contract\PromiseInterface
    {
        $this->resolution->apply($callback);
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
