const badgePalette = [
    'bg-monastic-saffron text-on-primary',
    'bg-tertiary text-on-tertiary',
    'bg-academic-maroon text-on-primary',
    'bg-surface-tint text-on-primary',
];

export function badgeColorForCategory(categorySlug) {
    if (!categorySlug) return badgePalette[0];

    let hash = 0;
    for (let i = 0; i < categorySlug.length; i += 1) {
        hash = (hash * 31 + categorySlug.charCodeAt(i)) >>> 0;
    }

    return badgePalette[hash % badgePalette.length];
}
