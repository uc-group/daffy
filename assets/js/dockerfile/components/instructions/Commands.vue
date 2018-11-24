<template>
    <div class="md-layout md-gutter">
        <div class="md-layout-item">
            <md-field>
                <label>Source</label>
                <md-textarea v-model="commands"></md-textarea>
            </md-field>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['value'],
        computed: {
            commands: {
                get() {
                    return this.value.commands
                },
                set(value) {
                    this.$emit('input', {
                        commands: value
                    })
                }
            }
        }
    }
</script>
