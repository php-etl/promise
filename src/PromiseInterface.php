<?php

namespace Kiboko\Component\ETL\Promise;

use Kiboko\Component\ETL\Promise\Resolution;

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