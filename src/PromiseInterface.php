<?php

namespace Kiboko\Component\Promise;

use Kiboko\Component\Promise\Resolution;

/**
 * @api
 */
interface PromiseInterface
{
    public function then(callable $callback): PromiseInterface;
    public function failure(callable $callback): PromiseInterface;
    public function defer(): DeferredInterface;
    public function isResolved(): bool;
    public function isSuccess(): bool;
    public function isFailure(): bool;
    public function resolution(): Resolution\ResolutionInterface;
}
