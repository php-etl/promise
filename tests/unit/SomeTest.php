<?php declare(strict_types=1);

namespace unit\Kiboko\Component\Promise;

use Kiboko\Contract\Promise\Resolution\FailureInterface;
use Kiboko\Contract\Promise\Resolution\SuccessInterface;
use PHPUnit\Framework\TestCase;
use Kiboko\Component\Promise as Component;

final class SomeTest extends TestCase
{
    public function testSucceededPromise()
    {
        $promise = Component\some([
            $childPromise1 = new Component\Promise(),
            $childPromise2 = new Component\Promise(),
            new Component\SucceededPromise('Resolved Value'),
            new Component\FailedPromise(new \Exception()),
        ], 3);

        $this->assertfalse($promise->isResolved());

        $childPromise1->resolve('success');

        $this->assertTrue($promise->isResolved());
        $this->assertInstanceOf(SuccessInterface::class, $promise->resolution());
        $this->assertEquals(['success', $childPromise2, 'Resolved Value', new \Exception()], $promise->resolution()->value());
    }

    public function testFailedPromise()
    {
        $promise = Component\some([
            $childPromise1 = new Component\Promise(),
            $childPromise2 = new Component\Promise(),
            new Component\SucceededPromise('Resolved Value'),
            new Component\FailedPromise(new \Exception()),
        ], 3);

        $this->assertfalse($promise->isResolved());

        $childPromise1->fail(new \Exception());

        $this->assertTrue($promise->isResolved());
        $this->assertInstanceOf(SuccessInterface::class, $promise->resolution());
        $this->assertEquals([new \Exception(), $childPromise2, 'Resolved Value', new \Exception()], $promise->resolution()->value());
    }
}
