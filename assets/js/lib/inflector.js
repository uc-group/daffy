export const Inflector = {
    toNormalizedHyphenCase(name) {
        return name
            .toLowerCase()
            .replace(/\s+/g, ' ')
            .replace(/\s|\./g, '-')
            .replace(/[^a-z0-9\-]/g, '')
        ;
    }
};
