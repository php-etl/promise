<?php declare(strict_types=1);

namespace spec\Kiboko\Component\Promise;

use Kiboko\Component\Promise\SucceededPromise;
use Kiboko\Contract\Promise\DeferredInterface;
use Kiboko\Contract\Promise\PromiseInterface;
use Kiboko\Contract\Promise\ResolvablePromiseInterface;
use PhpSpec\ObjectBehavior;

final class SucceededPromiseSpec extends ObjectBehavior
{
    function it_is_initializable(PromiseInterface $promise)
    {
        $this->beConstructedWith('succeeded');
        $this->shouldHaveType(SucceededPromise::class);
        $this->shouldHaveType(PromiseInterface::class);
        $this->shouldNotHaveType(ResolvablePromiseInterface::class);
    }

    function it_returns_a_deferred_object()
    {
        $this->beConstructedWith('succeeded');
        $this->defer()->shouldReturnAnInstanceOf(DeferredInterface::class);
    }

    function it_must_not_invoke_failure_callbacks(TestInvokable $invokable)
    {
        $this->beConstructedWith('succeeded');
        $invokable->__invoke(new \Exception())->shouldNotBeCalled();

        $this->failure($invokable)->shouldReturnAnInstanceOf(PromiseInterface::class);
    }

    function it_must_be_executed_immediately(TestInvokable $invokable)
    {
        $this->beConstructedWith('succeeded');
        $invokable->__invoke('succeeded')->shouldBeCalledOnce();

        $this->then($invokable)->shouldReturnAnInstanceOf(PromiseInterface::class);
    }

    function it_returns_resolution_success()
    {
        $this->beConstructedWith('succeeded');
        $this->isResolved()->shouldReturn(true);
        $this->isSuccess()->shouldReturn(true);
        $this->isFailure()->shouldReturn(false);
    }
}
