# Profiler package

TODO

## Peak memory and differences between points

`getMemoryPeakBytes()` returns the memory peak recorded at the last `mark()`. Before the first point is marked it
returns the current peak of the script (`memory_get_peak_usage()`, with the profiler's real-usage flag) instead of
`null`.

`getTimeBetween()` and `getMemoryBytesBetween()` return the **absolute** difference between two points, so the order of
the arguments does not matter and the result carries no direction: memory freed between two points is reported as the
same positive number as memory allocated. Compare the points yourself if the direction matters:

```php
$delta = $profiler->getPoint('after')->getMemoryBytes() - $profiler->getPoint('before')->getMemoryBytes();
```

Both methods throw a `LogicException` if either point was not marked. They are not part of `ProfilerInterface`.
