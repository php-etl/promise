<?php declare(strict_types=1);

namespace Kiboko\Component\ETL\Promise\Resolution;

/**
 * @internal
 */
final class Success implements SuccessInterface
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function value()
    {
        return $this->value;
    }
}