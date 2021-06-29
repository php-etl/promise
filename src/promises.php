<?php declare(strict_types=1);

namespace Kiboko\Component\Promise;

use Kiboko\Contract\Promise\PromiseInterface;

/**
 * @param array<int, PromiseInterface<mixed, \Throwable>> $promises
 * @return PromiseInterface<array<int, mixed>, \Throwable>
 */
function all(array $promises): PromiseInterface
{
    $promise = new Promise();

    $count = count($promises);
    /** @var \SplFixedArray<mixed|PromiseInterface<mixed, \Throwable>> $bucket */
    $bucket = new \SplFixedArray($count);

    foreach ($promises as $index => $deferred) {
        $bucket[$index] = $deferred;

        $deferred
            ->failure(function ($failure) use ($promise, $bucket, $index, &$count) {
                $bucket[$index] = $failure;

                if (--$count <= 0) {
                    $promise->resolve($bucket->toArray());
                }
            })
            ->then(function ($value) use ($promise, $bucket, $index, &$count) {
                $bucket[$index] = $value;

                if (--$count <= 0) {
                    $promise->resolve($bucket->toArray());
                }
            });
    }

    return $promise;
}

/**
 * @param array<PromiseInterface<mixed, \Throwable>> $promises
 * @return PromiseInterface<array<mixed>, \Throwable>
 */
function any(array $promises): PromiseInterface
{
    return namespace\some($promises, 1);
}

/**
 * @param array<PromiseInterface<mixed, \Throwable>> $promises
 * @return PromiseInterface<array<mixed>, \Throwable>
 */
function race(array $promises): PromiseInterface
{
    $promise = new Promise();

    foreach ($promises as $index => $deferred) {
        $bucket[$index] = $deferred;

        $deferred
            ->failure(function ($failure) use ($promise) {
                $promise->fail($failure);
            })
            ->then(function ($value) use ($promise) {
                $promise->resolve($value);
            });
    }

    return $promise;
}

/**
 * @param array<int, PromiseInterface<mixed, \Throwable>> $promises
 * @return PromiseInterface<array<mixed>, \Throwable>
 */
function some(array $promises, int $count = 1): PromiseInterface
{
    $promise = new Promise();

    $total = $pendingResolution = count($promises);
    /** @var \SplFixedArray<mixed|PromiseInterface<mixed, \Throwable>> $bucket */
    $bucket = new \SplFixedArray($pendingResolution);

    foreach ($promises as $index => $deferred) {
        $bucket[$index] = $deferred;

        $deferred
            ->failure(function ($failure) use ($promise, $bucket, $index, $count, $total, &$pendingResolution) {
                $bucket[$index] = $failure;
                --$pendingResolution;

                if (($pendingResolution <= 0 || ($total - $pendingResolution) >= $count)
                    && !$promise->isResolved()
                ) {
                    $promise->resolve($bucket->toArray());
                }
            })
            ->then(function ($value) use ($promise, $bucket, $index, $count, $total, &$pendingResolution) {
                $bucket[$index] = $value;
                --$pendingResolution;

                if (($pendingResolution <= 0 || ($total - $pendingResolution) >= $count)
                    && !$promise->isResolved()
                ) {
                    $promise->resolve($bucket->toArray());
                }
            });
    }

    return $promise;
}

/**
 * @param array<PromiseInterface<mixed, \Throwable>> $promises
 * @param callable(mixed|\Throwable|PromiseInterface<mixed, \Throwable>): PromiseInterface<mixed, \Throwable> $callback
 * @return PromiseInterface<array<mixed|\Throwable>, \Throwable>
 */
function map(array $promises, callable $callback): PromiseInterface
{
    $promise = new Promise();

    namespace\all($promises)
        ->then(function (array $values) use ($promise, $callback) {
            $promise->resolve(array_map($callback, $values));
        });

    return $promise;
}

/**
 * @template Type
 *
 * @param array<int, PromiseInterface<mixed, \Throwable>> $promises
 * @param callable $callback
 * @param null|Type $seed
 * @return PromiseInterface<null|Type, \Throwable>
 */
function reduce(array $promises, callable $callback, $seed = null): PromiseInterface
{
    $promise = new Promise();

    namespace\all($promises)
        ->then(function (array $values) use ($promise, $callback, $seed) {
            $promise->resolve(array_reduce($values, $callback, $seed));
        });

    return $promise;
}
