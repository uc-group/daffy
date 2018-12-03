<template>
    <div>
        <md-field>
            <label>Instruction</label>
            <md-select v-model="instruction">
                <md-option :value="key" :key="key" v-for="(label, key) in labels">{{  label }}</md-option>
            </md-select>
        </md-field>
        <component :is="argComponent" v-model="args" v-bind="config.props || false" v-if="config"></component>
    </div>
</template>

<script>
    import _ from 'lodash';
    import instructionConfig from '../../instruction-config';

    export default {
        props: ['value'],
        computed: {
            labels() {
                return _.mapValues(instructionConfig, 'label');
            },
            config() {
                return instructionConfig[this.instruction] || null;
            },
            argComponent() {
                return this.config.component || null;
            },
            instruction: {
                get() {
                    return this.value.instruction;
                },
                set(value) {
                    this.$emit('input', {
                        instruction: value
                    });
                }
            },
            args: {
                get() {
                    if (!this.config) {
                        return null;
                    }

                    if (this.config.hasOwnProperty('transformer')) {
                        return this.config.transformer.from(this.value);
                    }

                    return this.value;
                },
                set(value) {
                    if (!this.config) {
                        return null;
                    }

                    if (this.config.hasOwnProperty('transformer')) {
                        value = this.config.transformer.to(value);
                    }

                    value.instruction = this.instruction;
                    this.$emit('input', value);
                }
            }
        }
    }
</script>
