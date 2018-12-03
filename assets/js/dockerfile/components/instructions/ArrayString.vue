<template>
    <div class="md-layout md-gutter">
        <div class="md-layout-item">
            <md-field :key="index" v-for="(source, index) in localState" md-clearable>
                <label>Source {{ (index + 1) }}</label>
                <md-input v-model="localState[index]" @input="update"></md-input>
            </md-field>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['value', 'propertyName'],
        data() {
            return {
                localState: [...this.value[this.propertyName] || [], null]
            }
        },
        computed: {
            values() {
                return _.filter(this.localState, (element) => {
                    return !!element;
                });
            }
        },
        methods: {
            update() {
                const newValue = {};
                newValue[this.propertyName] = this.values;
                this.$emit('input', newValue);
            }
        },
        watch: {
            value(to) {
                this.localState = [...to[this.propertyName] || [], null];
            }
        }
    }
</script>
