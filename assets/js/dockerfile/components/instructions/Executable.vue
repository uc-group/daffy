<template>
    <div class="md-layout md-gutter">
        <div class="md-layout-item">
            <md-field>
                <label>Executable</label>
                <md-input v-model="executable"></md-input>
            </md-field>
        </div>
        <div class="md-layout-item">
            <md-field :key="index" v-for="(param, index) in localParams" md-clearable>
                <label>Param {{ (index + 1) }}</label>
                <md-input v-model="localParams[index]" @input="updateParams"></md-input>
            </md-field>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['value'],
        data() {
            return {
                localParams: [...this.value.params || [], null]
            }
        },
        computed: {
            params() {
                return _.filter(this.localParams, (element) => {
                    return !!element;
                });
            },
            executable: {
                get() {
                    return this.value.executable
                },
                set(value) {
                    const newValue = _.clone(this.value);
                    newValue.executable = value;
                    if (!newValue.hasOwnProperty('params')) {
                        newValue.params = [];
                    }
                    this.$emit('input', newValue);
                }
            }
        },
        methods: {
            updateParams() {
                const newValue = _.clone(this.value);
                newValue.params = this.params;
                this.$emit('input', newValue);
            }
        },
        watch: {
            value(to) {
                this.localParams = [...to.params || [], null];
            }
        }
    }
</script>
