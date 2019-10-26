<?php

namespace Kiboko\Component\ETL\Promise;

interface DeferredInterface
{
    public function then(callable $callback): DeferredInterface;
    public function failure(callable $callback): DeferredInterface;
}