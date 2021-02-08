<?php declare(strict_types=1);

namespace Kiboko\Component\Promise\Resolution;

use Kiboko\Contract\Promise\Resolution\SuccessInterface;

/**
 * @internal
 */
final class Success implements SuccessInterface
{
    public function __construct(private $value)
    {
    }

    public function value()
    {
        return $this->value;
    }
}
