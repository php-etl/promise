<?php

namespace Kiboko\Component\Promise;

/**
 * @api
 */
interface DeferredInterface
{
    public function then(callable $callback): DeferredInterface;
    public function failure(callable $callback): DeferredInterface;
}
