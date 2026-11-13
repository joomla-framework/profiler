# Profiler package

TODO

## The default renderer

`Joomla\Profiler\Renderer\DefaultRenderer` renders one `<code>` line per point with the time and memory since the
start and since the previous point. The profiler name and the point names are HTML-escaped
(`htmlspecialchars()` with `ENT_QUOTES | ENT_SUBSTITUTE`, UTF-8), so a point name built from request data, such as
`$profiler->mark('view: ' . $input->getString('view'))`, cannot inject markup into the page.
