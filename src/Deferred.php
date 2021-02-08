<?php declare(strict_types=1);

namespace Kiboko\Component\Promise;

use Kiboko\Contract\Promise\DeferredInterface;
use Kiboko\Contract\Promise\PromiseInterface;

/**
 * @api
 */
final class Deferred implements DeferredInterface
{
    public function __construct(private PromiseInterface $promise)
    {
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
