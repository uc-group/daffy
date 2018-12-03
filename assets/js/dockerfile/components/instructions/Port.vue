<template>
    <div class="md-layout md-gutter">
        <div class="md-layout-item">
            <md-field>
                <label>Port</label>
                <md-input v-model="port" type="number" min="1"></md-input>
            </md-field>
        </div>
        <div class="md-layout-item">
            <md-field>
                <label>Protocol</label>
                <md-select v-model="protocol">
                    <md-option value="tcp">TCP</md-option>
                    <md-option value="udp">UDP</md-option>
                </md-select>
            </md-field>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['value'],
        computed: {
            port: {
                get() {
                    return this.value.port
                },
                set(value) {
                    this.$emit('input', {
                        port: value,
                        protocol: this.protocol || 'tcp'
                    })
                }
            },
            protocol: {
                get() {
                    return this.value.protocol || 'tcp'
                },
                set(value) {
                    this.$emit('input', {
                        port: this.port,
                        protocol: value
                    });
                }
            }
        }
    }
</script>
