<?php

namespace spec\Kiboko\Component\ETL\Promise;

use Kiboko\Component\ETL\Promise\AlreadyResolvedPromise;
use Kiboko\Component\ETL\Promise\DeferredInterface;
use Kiboko\Component\ETL\Promise\Promise;
use Kiboko\Component\ETL\Promise\PromiseInterface;
use Kiboko\Component\ETL\Promise\ResolvablePromiseInterface;
use PhpSpec\ObjectBehavior;

final class PromiseSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Promise::class);
        $this->shouldImplement(PromiseInterface::class);
        $this->shouldImplement(ResolvablePromiseInterface::class);
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

    function it_must_not_be_resolved_twice()
    {
        $this->resolve('resolved');

        $this->shouldThrow(new AlreadyResolvedPromise('The promise was already resolved, cannot resolve again.'))
            ->during('resolve', ['resolved']);
    }

    function it_must_not_throw_exceptions_during_resolution()
    {
        $failure = new \Exception();
        $exception = new \Exception();
        $invokable = new TestBrokenInvokable($failure);

        $this->then($invokable)->shouldReturnAnInstanceOf(PromiseInterface::class);

        $this->shouldThrow(new \RuntimeException('A promise handler should not throw exceptions.', 0, $exception))
            ->during('resolve', ['resolved']);
    }

    function it_must_be_executed_immediately_when_resolved(TestInvokable $invokable)
    {
        $invokable->__invoke('resolved')->shouldBeCalledOnce();

        $this->resolve('resolved');

        $this->then($invokable)->shouldReturnAnInstanceOf(PromiseInterface::class);
    }

    function it_can_return_resolution_success()
    {
        $this->resolve('resolved');

        $this->isResolved()->shouldReturn(true);
        $this->isSuccess()->shouldReturn(true);
        $this->isFailure()->shouldReturn(false);
    }

    function it_can_be_failed(TestInvokable $invokable)
    {
        $exception = new \Exception();
        $invokable->__invoke($exception)->shouldBeCalledOnce();

        $this->failure($invokable)->shouldReturnAnInstanceOf(PromiseInterface::class);

        $this->fail($exception);
    }

    function it_must_not_be_failed_twice()
    {
        $exception = new \Exception();
        $this->fail($exception);

        $this->shouldThrow(new AlreadyResolvedPromise('The promise was already resolved, cannot resolve again.'))
            ->during('fail', [$exception]);
    }

    function it_must_not_throw_exceptions_during_failure()
    {
        $failure = new \Exception();
        $exception = new \Exception();
        $invokable = new TestBrokenInvokable($failure);

        $this->failure($invokable)->shouldReturnAnInstanceOf(PromiseInterface::class);

        $this->shouldThrow(new \RuntimeException('A promise handler should not throw exceptions.', 0, $exception))
            ->during('fail', [$failure]);
    }

    function it_must_be_executed_immediately_when_failed(TestInvokable $invokable)
    {
        $exception = new \Exception();
        $invokable->__invoke($exception)->shouldBeCalledOnce();

        $this->fail($exception);

        $this->failure($invokable)->shouldReturnAnInstanceOf(PromiseInterface::class);
    }

    function it_can_return_resolution_failure()
    {
        $this->fail(new \Exception());

        $this->isResolved()->shouldReturn(true);
        $this->isSuccess()->shouldReturn(false);
        $this->isFailure()->shouldReturn(true);
    }
}
