# Updating from v3 to v4

Release 4.0.0 raises the PHP requirement. No method signature changed, but one runtime check was
removed.

## At a glance

| | v3 (3.0.2) | v4 (4.0.0) |
|---|---|---|
| PHP | `^8.1.0` | `^8.3.0` |
| Method signatures | — | unchanged |
| `setPoints()` type check | throws on a wrong element | **removed** |

## Minimum supported PHP version raised

All Framework packages now require **PHP 8.3** or newer.

## `setPoints()` no longer validates its elements

The constructor accepts an array of pre-built points and passes it to the protected
`setPoints()`. In 3.x that method rejected anything that was not a `ProfilePointInterface`:

```php
// Removed in 4.0.0
if (!($point instanceof ProfilePointInterface)) {
    throw new \InvalidArgumentException(
        'One of the passed points does not implement ProfilePointInterface.'
    );
}
```

Without it, a wrong element is no longer reported where it is introduced. The next access —
`$point->getName()` inside `setPoints()` — raises an `Error` instead, and for a non-object the
message points at the profiler rather than at the caller who supplied the array.

The docblock still advertises `@throws \InvalidArgumentException`, so do not rely on catching it:

```php
// This no longer throws InvalidArgumentException
$profiler = new Profiler('app', null, [$aProfilePoint, 'not a point']);
```

Validate the array yourself if it comes from anywhere other than your own code.

## `DefaultRenderer` internals reshuffled

The renderer's tracking of the previous point was rewritten. The rendered output is unchanged, so
this only matters if you subclassed `DefaultRenderer` and relied on its internals.

## Dependency changes

| Package | v3 (3.0.2) | v4 (4.0.0) |
|---|---|---|
| `php` | `^8.1.0` | `^8.3.0` |

The package still has no runtime dependencies beyond PHP itself.
