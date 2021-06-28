<?php declare(strict_types=1);

namespace Kiboko\Component\Promise\Resolution;

use Kiboko\Contract\Promise as Contract;

/**
 * @internal
 * @template Type
 * @implements Contract\Resolution\SuccessInterface<Type>
 */
final class Success implements Contract\Resolution\SuccessInterface
{
    /** @param Type $value */
    public function __construct(private $value)
    {
    }

    /** @return Type */
    public function value()
    {
        return $this->value;
    }

    /** @var callable(Type): void */
    public function apply(callable $callback): void
    {
        $callback($this->value);
    }
}
