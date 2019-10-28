<?php declare(strict_types=1);

namespace Kiboko\Component\ETL\Promise;

use Kiboko\Component\ETL\Promise\Resolution;

/**
 * @api
 */
final class FailedPromise implements PromiseInterface
{
    /** @var Resolution\FailureInterface */
    private $resolution;

    public function __construct(\Throwable $exception)
    {
        $this->resolution = new Resolution\Failure($exception);
    }

    public function then(callable $callback): PromiseInterface
    {
        return $this;
    }

    public function failure(callable $callback): PromiseInterface
    {
        $callback($this->resolution->error());
        return $this;
    }

    public function defer(): DeferredInterface
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

    public function resolution(): Resolution\ResolutionInterface
    {
        return $this->resolution;
    }
}