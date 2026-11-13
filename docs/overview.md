# Profiler package

TODO

## The start reference

Every point records the time and memory **relative to a start reference**. By default the first `mark()` sets that
reference, so the first point has a time and memory usage of zero.

`setStart()` sets the reference before the first point is marked and adds a point named `start` at zero. Both
arguments are optional; an omitted argument means "now":

```php
use Joomla\Profiler\Profiler;

$profiler = new Profiler('app');

// Measure from the start of the request instead of from now
$profiler->setStart($_SERVER['REQUEST_TIME_FLOAT']);

// Measure from now; same as $profiler->setStart(microtime(true), memory_get_usage())
$profiler->setStart();
```

`setStart()` throws a `RuntimeException` once a point exists.

When points are passed to the constructor, points marked later are measured from the moment the profiler was
constructed.
