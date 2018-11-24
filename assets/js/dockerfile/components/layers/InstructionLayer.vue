<template>
    <div>
        <md-field>
            <label>Instruction</label>
            <md-select v-model="instruction">
                <md-option value="add">ADD</md-option>
                <md-option value="run">RUN</md-option>
            </md-select>
        </md-field>
        <component :is="argComponent" v-model="args"></component>
    </div>
</template>

<script>
    import SourceDestination from '../instructions/SourceDestination';
    import Commands from '../instructions/Commands';

    const instructionArguments = {
        add: SourceDestination,
        run: Commands,
    };

    export default {
        props: ['value'],
        computed: {
            argComponent() {
                return instructionArguments[this.instruction] || false;
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
                    return this.value;
                },
                set(value) {
                    value.instruction = this.instruction;
                    this.$emit('input', value);
                }
            }
        }
    }
</script>
