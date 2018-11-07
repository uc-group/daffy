export const Inflector = {
    toNormalizedHyphenCase(name) {
        return name
            .toLowerCase()
            .replace(/[^a-z0-9_\s.]/g, '')
            .replace(/\s+|\.+/g, '_')
            .replace(/_+/g, '_')
        ;
    }
};
