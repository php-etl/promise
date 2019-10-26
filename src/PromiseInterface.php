<?php

namespace Kiboko\Component\ETL\Promise;

interface PromiseInterface
{
    public function then(callable $callback): PromiseInterface;
    public function failure(callable $callback): PromiseInterface;
    public function defer(): DeferredInterface;
    public function resolve($value): void;
    public function fail(\Throwable $failure): void;
    public function isResolved(): bool;
}