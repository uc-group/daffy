<template>
    <div>
        <md-checkbox :key="extension" v-for="extension in availableExtensions"
                     v-model="extensions" :value="extension">{{ extension }}</md-checkbox>
    </div>
</template>

<script>
    export default {
        props: ['value'],
        data() {
            return {
                availableExtensions: ['mcrypt', 'pdo_mysqli', 'intl', 'xsl', 'gd', 'zip', 'exif', 'soap', 'zmq', 'xdebug']
            }
        },
        computed: {
            extensions: {
                get() {
                    return this.value.extensions
                },
                set(value) {
                    this.$emit('input', {
                        extensions: value
                    });
                }
            }
        }
    }
</script>
