<template>
    <div class="md-layout md-gutter">
        <div class="md-layout-item">
            <md-field>
                <label>Source</label>
                <md-textarea v-model="localValue"></md-textarea>
            </md-field>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['value', 'propertyName'],
        computed: {
            localValue: {
                get() {
                    return this.value[this.propertyName]
                },
                set(value) {
                    let obj = {};
                    obj[this.propertyName] = value;
                    this.$emit('input', obj)
                }
            }
        }
    }
</script>
