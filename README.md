Promises implementation
===

The Promise pattern is helping when you need to  organise your callbacks for 
when some tasks must be executed later.

Assuming we have this class with a `doSomethingAsync` method 
that will produce later an call to `onSuccess` event handler.

```php
<?php

use Kiboko\Component\ETL\Promise\DeferredInterface;
use Kiboko\Component\ETL\Promise\Promise;
use Kiboko\Component\ETL\Promise\PromiseInterface;

class SomeEvent
{
    public $value;
}


class AsyncTask
{
    /** @var PromiseInterface */
    private $promise;

    public function doSomethingAsync(): DeferredInterface
    {
        // Do something
        $this->promise = new Promise();

        return $this->promise->defer();
    }

    public function onSuccess(SomeEvent $event)
    {
        $this->promise->resolve($event->value);
    }
}
```

You can then register the following 

```php
<?php
$task = new AsyncTask();
$task
    ->doSomethingAsync()
    ->then(
        function(string $value) {
            echo $value;
            return $value;
        }
    );
```
