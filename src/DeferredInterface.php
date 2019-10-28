<?php

namespace Kiboko\Component\ETL\Promise;

/**
 * @api
 */
interface DeferredInterface
{
    public function then(callable $callback): DeferredInterface;
    public function failure(callable $callback): DeferredInterface;
}