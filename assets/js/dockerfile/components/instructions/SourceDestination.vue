<template>
    <div class="md-layout md-gutter">
        <div class="md-layout-item">
            <md-field>
                <label>Source</label>
                <md-input v-model="source"></md-input>
            </md-field>
        </div>
        <div class="md-layout-item">
            <md-field>
                <label>Destination</label>
                <md-input v-model="destination"></md-input>
            </md-field>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['value'],
        computed: {
            source: {
                get() {
                    return this.value.source
                },
                set(value) {
                    this.$emit('input', {
                        source: value,
                        destination: this.destination
                    })
                }
            },
            destination: {
                get() {
                    return this.value.destination
                },
                set(value) {
                    this.$emit('input', {
                        source: this.source,
                        destination: value
                    });
                }
            }
        }
    }
</script>
