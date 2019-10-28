<?php

namespace Kiboko\Component\ETL\Promise;

/**
 * @api
 */
interface ResolvablePromiseInterface extends PromiseInterface
{
    public function resolve($value): void;
    public function fail(\Throwable $failure): void;
}