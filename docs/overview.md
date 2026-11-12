# Overview

The Profiler package records named points in time, each with the elapsed time and the memory
allocated since the first point, and renders them.

```bash
composer require joomla/profiler
```

## What is in the package

| Class | Purpose |
|---|---|
| `Joomla\Profiler\Profiler` | Collects the points; `Countable` and `IteratorAggregate` |
| `Joomla\Profiler\ProfilerInterface` | The profiler contract |
| `Joomla\Profiler\ProfilePoint` | One measurement: name, time, memory |
| `Joomla\Profiler\ProfilePointInterface` | The point contract |
| `Joomla\Profiler\ProfilerRendererInterface` | The renderer contract |
| `Joomla\Profiler\Renderer\DefaultRenderer` | Renders the points as HTML |

## Measuring

```php
use Joomla\Profiler\Profiler;

$profiler = new Profiler('application');

$profiler->mark('start');
// … bootstrap …
$profiler->mark('afterBootstrap');
// … route and dispatch …
$profiler->mark('afterDispatch');
```

The first `mark()` establishes the baseline; every later point reports the time and memory
**relative to it**. Times are in seconds as floats, memory in bytes.

## Reading the results

```php
$profiler->getPoints();                                  // ProfilePointInterface[]
$profiler->hasPoint('afterBootstrap');                   // bool
$profiler->getPoint('afterBootstrap');                   // the point, or a default
$profiler->getMemoryPeakBytes();                         // peak during the run

$profiler->getTimeBetween('start', 'afterDispatch');     // seconds
$profiler->getMemoryBytesBetween('start', 'afterDispatch');   // bytes

count($profiler);                                        // number of points

foreach ($profiler as $point) {
    printf("%s: %.3fs, %.2f MB\n", $point->getName(), $point->getTime(), $point->getMemoryMegaBytes());
}
```

Both `*Between()` methods return `abs()` of the difference, so the result carries no direction —
a drop in memory looks the same as a rise, and the order of the two arguments does not matter.
They throw a `LogicException` if either point was never marked.

`getMemoryPeakBytes()` returns `null` until the first `mark()`.

## A point

```php
$point->getName();               // string
$point->getTime();               // float, seconds since the first point
$point->getMemoryBytes();        // int
$point->getMemoryMegaBytes();    // float
```

`ProfilePoint` is immutable — the values are set in the constructor and only read afterwards.

## Rendering

```php
echo $profiler->render();     // uses the configured renderer
echo $profiler;               // __toString() does the same
```

`DefaultRenderer` produces one `<code>` line per point with the cumulative and per-step figures.

> The default renderer does **not escape** the profiler name or the point names. If a point name
> can contain request data — `mark('view: ' . $input->getString('view'))` is a natural thing to
> write — that value lands unescaped in the page. Either keep point names static, or supply a
> renderer that escapes.

Writing your own renderer is a single method:

```php
use Joomla\Profiler\ProfilerInterface;
use Joomla\Profiler\ProfilerRendererInterface;

final class JsonRenderer implements ProfilerRendererInterface
{
    public function render(ProfilerInterface $profiler): string
    {
        $points = array_map(
            static fn($point) => [
                'name'   => $point->getName(),
                'time'   => round($point->getTime(), 4),
                'memory' => $point->getMemoryBytes(),
            ],
            $profiler->getPoints()
        );

        return json_encode(['profiler' => $profiler->getName(), 'points' => $points], JSON_THROW_ON_ERROR);
    }
}

$profiler->setRenderer(new JsonRenderer());
```

## Constructor options

```php
new Profiler(
    string $name,
    ?ProfilerRendererInterface $renderer = null,   // defaults to DefaultRenderer
    array $points = [],                            // pre-seeded points
    bool $memoryRealUsage = false                  // pass true for real allocated memory
);
```

`$memoryRealUsage` is handed to `memory_get_usage()` and `memory_get_peak_usage()`: `false`
reports what PHP's allocator handed out, `true` what it requested from the system.

## Things to know

**A point name can only be used once.** `mark()` throws an `InvalidArgumentException` for a name
that already exists, so measuring the same label inside a loop needs a counter in the name.

**`setStart()` defaults are not useful.** The signature is
`setStart($timeStamp = 0.0, $memoryBytes = 0)`, and calling it without arguments sets the baseline
to the Unix epoch — every subsequent point then reports about 1.7 billion seconds. Pass real
values, or do not call it at all and let the first `mark()` set the baseline.

**Points passed to the constructor are not validated.** The constructor does not check that the
array contains `ProfilePointInterface` instances, so a wrong element surfaces later as an `Error`
rather than as the documented `InvalidArgumentException`.

## Not part of this package

* No nested sections or spans — the profiler records a flat list, so a call tree cannot be
  expressed.
* No renderer other than the HTML one, and no null implementation for production.
* No integration with the rest of the framework: nothing hooks the application events, the
  database query monitor or PSR-3 automatically.
* No aggregation — no totals, percentages or ranking of the slowest steps.
