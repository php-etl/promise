<?php declare(strict_types=1);

namespace spec\Kiboko\Component\Promise;

use Kiboko\Component\Promise\FailedPromise;
use Kiboko\Contract\Promise\DeferredInterface;
use Kiboko\Contract\Promise\PromiseInterface;
use Kiboko\Contract\Promise\ResolvablePromiseInterface;
use PhpSpec\ObjectBehavior;

final class FailedPromiseSpec extends ObjectBehavior
{
    function it_is_initializable(PromiseInterface $promise)
    {
        $this->beConstructedWith(new \Exception());
        $this->shouldHaveType(FailedPromise::class);
        $this->shouldHaveType(PromiseInterface::class);
        $this->shouldNotHaveType(ResolvablePromiseInterface::class);
    }

    function it_returns_a_deferred_object()
    {
        $this->beConstructedWith(new \Exception());
        $this->defer()->shouldReturnAnInstanceOf(DeferredInterface::class);
    }

    function it_must_not_invoke_resolution_callbacks(TestInvokable $invokable)
    {
        $this->beConstructedWith(new \Exception());
        $invokable->__invoke('resolved')->shouldNotBeCalled();

        $this->then($invokable)->shouldReturnAnInstanceOf(PromiseInterface::class);
    }

    function it_must_be_executed_immediately(TestInvokable $invokable)
    {
        $exception = new \Exception();
        $this->beConstructedWith($exception);
        $invokable->__invoke($exception)->shouldBeCalledOnce();

        $this->failure($invokable)->shouldReturnAnInstanceOf(PromiseInterface::class);
    }

    function it_returns_resolution_success()
    {
        $this->beConstructedWith(new \Exception());
        $this->isResolved()->shouldReturn(true);
        $this->isSuccess()->shouldReturn(false);
        $this->isFailure()->shouldReturn(true);
    }
}
