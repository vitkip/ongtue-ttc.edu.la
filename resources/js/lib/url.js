// Prefixes app-relative paths with the base path the app is actually served
// under (see window.APP_BASE_PATH in resources/views/app.blade.php). Needed
// because this deployment can be hosted under a subdirectory rather than the
// domain root.
export function url(path) {
    const base = window.APP_BASE_PATH || '';
    return base + path;
}
