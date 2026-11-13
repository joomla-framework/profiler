# Profiler package

TODO

## Injecting points

The constructor's third argument takes an array of existing points, for example to render a profile that was recorded
elsewhere:

```php
use Joomla\Profiler\ProfilePoint;
use Joomla\Profiler\Profiler;

$profiler = new Profiler('import', null, [new ProfilePoint('start'), new ProfilePoint('parsed', 0.25, 524288)]);
```

Every element must implement `Joomla\Profiler\ProfilePointInterface` and every point name must be unique; otherwise
the constructor throws an `InvalidArgumentException`.
