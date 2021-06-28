<?php declare(strict_types=1);

namespace Kiboko\Component\Promise;

use Kiboko\Contract\Promise as Contract;

/**
 * @api
 * @template ExpectationType
 * @template ExceptionType of \Throwable
 * @implements Contract\DeferredInterface<ExpectationType, ExceptionType>
 */
final class Deferred implements Contract\DeferredInterface
{
    /** @param Contract\PromiseInterface<ExpectationType, ExceptionType> $promise */
    public function __construct(private Contract\PromiseInterface $promise)
    {
    }

    /**
     * @param callable(ExpectationType): void $callback
     *
     * @return Contract\DeferredInterface<ExpectationType, ExceptionType>
     */
    public function then(callable $callback): Contract\DeferredInterface
    {
        $this->promise->then($callback);
        return $this;
    }

    /**
     * @param callable(ExceptionType): void $callback
     *
     * @return Contract\DeferredInterface<ExpectationType, ExceptionType>
     */
    public function failure(callable $callback): Contract\DeferredInterface
    {
        $this->promise->failure($callback);
        return $this;
    }
}
