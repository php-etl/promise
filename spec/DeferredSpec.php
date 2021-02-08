<?php

namespace spec\Kiboko\Component\Promise;

use Kiboko\Component\Promise\Deferred;
use Kiboko\Component\Promise\Promise;
use Kiboko\Contract\Promise\DeferredInterface;
use Kiboko\Contract\Promise\PromiseInterface;
use PhpSpec\ObjectBehavior;

final class DeferredSpec extends ObjectBehavior
{
    function it_is_initializable(PromiseInterface $promise)
    {
        $this->beConstructedWith($promise);
        $this->shouldHaveType(Deferred::class);
        $this->shouldHaveType(DeferredInterface::class);
    }

    function it_can_be_resolved(TestInvokable $invokable)
    {
        $this->beConstructedWith($promise = new Promise());
        $invokable->__invoke('resolved')->shouldBeCalledOnce();

        $this->then($invokable)->shouldReturnAnInstanceOf(DeferredInterface::class);

        $promise->resolve('resolved');
    }

    function it_can_be_failed(TestInvokable $invokable)
    {
        $this->beConstructedWith($promise = new Promise());
        $invokable->__invoke(new \Exception())->shouldBeCalledOnce();

        $this->failure($invokable)->shouldReturnAnInstanceOf(DeferredInterface::class);

        $promise->fail(new \Exception());
    }
}
