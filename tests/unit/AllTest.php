<?php declare(strict_types=1);

namespace unit\Kiboko\Component\Promise;

use Kiboko\Contract\Promise\Resolution\SuccessInterface;
use PHPUnit\Framework\TestCase;
use Kiboko\Component\Promise as Component;

final class AllTest extends TestCase
{
    public function testSucceededPromise()
    {
        $promise = Component\all([
            $childPromise = new Component\Promise(),
            new Component\SucceededPromise('Resolved Value'),
            new Component\FailedPromise(new \Exception()),
        ]);

        $this->assertfalse($promise->isResolved());

        $childPromise->resolve('success');

        $this->assertTrue($promise->isResolved());
        $this->assertInstanceOf(SuccessInterface::class, $promise->resolution());
        $this->assertEquals(['success', 'Resolved Value', new \Exception()], $promise->resolution()->value());
    }

    public function testFailedPromise()
    {
        $promise = Component\all([
            $childPromise = new Component\Promise(),
            new Component\SucceededPromise('Resolved Value'),
            new Component\FailedPromise(new \Exception()),
        ]);

        $this->assertfalse($promise->isResolved());

        $childPromise->fail(new \Exception());

        $this->assertTrue($promise->isResolved());
        $this->assertInstanceOf(SuccessInterface::class, $promise->resolution());
        $this->assertEquals([new \Exception(), 'Resolved Value', new \Exception()], $promise->resolution()->value());
    }
}
