<?php declare(strict_types=1);

namespace Kiboko\Component\Promise;

use Kiboko\Contract\Promise as Contract;

/**
 * @api
 * @template Type
 * @implements Contract\DeferredInterface<Type>
 */
final class Deferred implements Contract\DeferredInterface
{
    /** @param Contract\PromiseInterface<Type> $promise */
    public function __construct(private Contract\PromiseInterface $promise)
    {
    }

    /**
     * @param callable(Type): Type $callback
     *
     * @return Contract\DeferredInterface<Type>
     */
    public function then(callable $callback): Contract\DeferredInterface
    {
        $this->promise->then($callback);
        return $this;
    }

    /**
     * @param callable(\Throwable):\Throwable $callback
     *
     * @return Contract\DeferredInterface<Type>
     */
    public function failure(callable $callback): Contract\DeferredInterface
    {
        $this->promise->failure($callback);
        return $this;
    }
}
