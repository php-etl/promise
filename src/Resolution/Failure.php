<?php declare(strict_types=1);

namespace Kiboko\Component\Promise\Resolution;

use Kiboko\Contract\Promise as Contract;

/**
 * @internal
 * @template Type of \Throwable
 * @implements Contract\Resolution\FailureInterface<Type>
 */
final class Failure implements Contract\Resolution\FailureInterface
{
    /** @param Type $error */
    public function __construct(private \Throwable $error)
    {
    }

    /** @return Type */
    public function error(): \Throwable
    {
        return $this->error;
    }

    /** @var callable(Type): void */
    public function apply(callable $callback): void
    {
        $callback($this->error);
    }
}
