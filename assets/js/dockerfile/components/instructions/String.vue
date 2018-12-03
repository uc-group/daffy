<template>
    <div class="md-layout md-gutter">
        <div class="md-layout-item">
            <md-field>
                <label>{{ label }}</label>
                <md-input v-model="localValue"></md-input>
            </md-field>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['value', 'propertyName', 'label'],
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
