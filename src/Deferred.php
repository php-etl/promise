<?php declare(strict_types=1);

namespace Kiboko\Component\Promise;

/**
 * @api
 */
final class Deferred implements DeferredInterface
{
    /** @var PromiseInterface */
    private $promise;

    public function __construct(PromiseInterface $deferred)
    {
        $this->promise = $deferred;
    }

    /**
     * @param callable<mixed> $callback
     *
     * @return DeferredInterface
     */
    public function then(callable $callback): DeferredInterface
    {
        $this->promise->then($callback);
        return $this;
    }

    /**
     * @param callable<\Throwable> $callback
     *
     * @return DeferredInterface
     */
    public function failure(callable $callback): DeferredInterface
    {
        $this->promise->failure($callback);
        return $this;
    }
}
