<?php declare(strict_types=1);

namespace Kiboko\Component\ETL\Promise\Resolution;

/**
 * @internal
 */
interface ResolvedInterface
{
    public function apply(callable $callback): void;
}
