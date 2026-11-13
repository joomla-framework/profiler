## Updating from v4 to v5

The following changes were made to the Profiler package between v4 and v5.

### Native parameter and return types

The interfaces and classes now declare native types instead of documenting them in doc blocks only, and
`Profiler::getIterator()` / `Profiler::count()` no longer need `#[\ReturnTypeWillChange]`. Classes implementing one of
the interfaces or extending one of the classes must add the same types to the methods they implement or override,
otherwise PHP raises a fatal error when the class is loaded.

| Class | Member | v4 | v5 |
|---|---|---|---|
| `ProfilerInterface`, `Profiler` | `getName()` | `getName()` | `getName(): string` |
| `ProfilerInterface`, `Profiler` | `mark()` | `mark($name)` | `mark(string $name): static` |
| `ProfilerInterface`, `Profiler` | `hasPoint()` | `hasPoint($name)` | `hasPoint(string $name): bool` |
| `ProfilerInterface`, `Profiler` | `getPoint()` | `getPoint($name, $default = null)` | `getPoint(string $name, mixed $default = null): mixed` |
| `ProfilerInterface`, `Profiler` | `getPoints()` | `getPoints()` | `getPoints(): array` |
| `ProfilerInterface`, `Profiler` | `setRenderer()` | `setRenderer(ProfilerRendererInterface $renderer)` | `setRenderer(ProfilerRendererInterface $renderer): static` |
| `ProfilerInterface`, `Profiler` | `getRenderer()` | `getRenderer()` | `getRenderer(): ProfilerRendererInterface` |
| `ProfilerInterface`, `Profiler` | `render()` | `render()` | `render(): string` |
| `ProfilerInterface`, `Profiler` | `getTimeBetween()` | `getTimeBetween($first, $second)` (`Profiler` only) | `getTimeBetween(string $first, string $second): float` |
| `ProfilerInterface`, `Profiler` | `getMemoryBytesBetween()` | `getMemoryBytesBetween($first, $second)` (`Profiler` only) | `getMemoryBytesBetween(string $first, string $second): int` |
| `Profiler` | `__construct()` | `__construct($name, ?ProfilerRendererInterface $renderer = null, array $points = [], $memoryRealUsage = false)` | `__construct(string $name, ?ProfilerRendererInterface $renderer = null, array $points = [], bool $memoryRealUsage = false)` |
| `Profiler` | `setPoints()` | `setPoints(array $points)` | `setPoints(array $points): void` |
| `Profiler` | `getMemoryPeakBytes()` | `getMemoryPeakBytes()` | `getMemoryPeakBytes(): ?int` |
| `Profiler` | `__toString()` | `__toString()` | `__toString(): string` |
| `Profiler` | `getIterator()` | `#[\ReturnTypeWillChange] getIterator()` | `getIterator(): \ArrayIterator` |
| `Profiler` | `count()` | `#[\ReturnTypeWillChange] count()` | `count(): int` |
| `Profiler` | `setStart()` | `setStart($timeStamp = 0.0, $memoryBytes = 0)` | `setStart(float $timeStamp = 0.0, int $memoryBytes = 0): static` |
| `ProfilePointInterface`, `ProfilePoint` | `getName()` | `getName()` | `getName(): string` |
| `ProfilePointInterface`, `ProfilePoint` | `getTime()` | `getTime()` | `getTime(): float` |
| `ProfilePointInterface`, `ProfilePoint` | `getMemoryBytes()` | `getMemoryBytes()` | `getMemoryBytes(): int` |
| `ProfilePointInterface`, `ProfilePoint` | `getMemoryMegaBytes()` | `getMemoryMegaBytes()` | `getMemoryMegaBytes(): float` |
| `ProfilePoint` | `__construct()` | `__construct($name, $time = 0.0, $memoryBytes = 0)` | `__construct(string $name, float $time = 0.0, int $memoryBytes = 0)` |
| `ProfilerRendererInterface`, `DefaultRenderer` | `render()` | `render(ProfilerInterface $profiler)` | `render(ProfilerInterface $profiler): string` |

### `getTimeBetween()` and `getMemoryBytesBetween()` are part of `ProfilerInterface`

Both methods existed on `Profiler` only. They are now declared by `ProfilerInterface`, so code that receives any
profiler can use them, and every class implementing `ProfilerInterface` directly must implement them. Both return the
absolute difference between the two points and throw a `LogicException` if a point was not marked.

For example, a renderer now declares its return type:

```php
use Joomla\Profiler\ProfilerInterface;
use Joomla\Profiler\ProfilerRendererInterface;

class TextRenderer implements ProfilerRendererInterface
{
    public function render(ProfilerInterface $profiler): string
    {
        // ...
    }
}
```
