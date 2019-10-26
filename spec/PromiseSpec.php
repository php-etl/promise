<?php

namespace spec\Kiboko\Component\ETL\Promise;

use Kiboko\Component\ETL\Promise\AlreadyResolvedPromise;
use Kiboko\Component\ETL\Promise\DeferredInterface;
use Kiboko\Component\ETL\Promise\Promise;
use Kiboko\Component\ETL\Promise\PromiseInterface;
use PhpSpec\ObjectBehavior;

class PromiseSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Promise::class);
        $this->shouldImplement(PromiseInterface::class);
    }

    function it_returns_a_deferred_object()
    {
        $this->defer()->shouldReturnAnInstanceOf(DeferredInterface::class);
    }

    function it_can_be_resolved(TestInvokable $invokable)
    {
        $invokable->__invoke('resolved')->shouldBeCalledOnce();

        $this->then($invokable)->shouldReturnAnInstanceOf(PromiseInterface::class);

        $this->resolve('resolved');
    }

    function it_must_not_be_resolved_twice(TestInvokable $invokable)
    {
        $this->resolve('resolved');

        $this->shouldThrow(new AlreadyResolvedPromise('The promise was already resolved, cannot resolve again.'))
            ->during('resolve', ['resolved']);
    }

    function it_can_be_failed(TestInvokable $invokable)
    {
        $invokable->__invoke(new \Exception())->shouldBeCalledOnce();

        $this->failure($invokable)->shouldReturnAnInstanceOf(PromiseInterface::class);

        $this->fail(new \Exception());
    }

    function it_must_not_be_failed_twice(TestInvokable $invokable)
    {
        $this->fail(new \Exception());

        $this->shouldThrow(new AlreadyResolvedPromise('The promise was already resolved, cannot resolve again.'))
            ->during('fail', [new \Exception()]);
    }
}

class TestInvokable
{
    public function __invoke($value)
    {
    }
}
