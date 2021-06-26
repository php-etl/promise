<?php declare(strict_types=1);

namespace Kiboko\Component\Promise\Resolution;

use Kiboko\Contract\Promise\Resolution\SuccessInterface;

/**
 * @internal
 * @template Type
 * @implements SuccessInterface<Type>
 */
final class Success implements SuccessInterface
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

    /** @var callable(Type) */
    public function apply(callable $callback): void
    {
        if (($value = $callback($this->value)) !== null) {
            $this->value = $value;
        }
    }
}
