<?php declare(strict_types=1);

namespace unit\Kiboko\Component\Promise;

use Kiboko\Contract\Promise\Resolution\FailureInterface;
use Kiboko\Contract\Promise\Resolution\SuccessInterface;
use PHPUnit\Framework\TestCase;
use Kiboko\Component\Promise as Component;

final class RaceTest extends TestCase
{
    public function testSucceededPromise()
    {
        $promise = Component\race([
            new Component\Promise(),
            new Component\Promise(),
            new Component\SucceededPromise('Resolved Value'),
        ]);

        $this->assertTrue($promise->isResolved());
        $this->assertInstanceOf(SuccessInterface::class, $promise->resolution());
        $this->assertEquals('Resolved Value', $promise->resolution()->value());
    }

    public function testFailedPromise()
    {
        $promise = Component\race([
            new Component\Promise(),
            new Component\Promise(),
            new Component\FailedPromise(new \Exception()),
        ]);

        $this->assertTrue($promise->isResolved());
        $this->assertInstanceOf(FailureInterface::class, $promise->resolution());
        $this->assertEquals(new \Exception(), $promise->resolution()->error());
    }
}
