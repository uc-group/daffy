<template>
    <div class="md-layout md-gutter">
        <div class="md-layout-item">
            <md-field>
                <label>{{ keyLabel }}</label>
                <md-input v-model="key"></md-input>
            </md-field>
        </div>
        <div class="md-layout-item">
            <md-field>
                <label>{{ valueLabel }}</label>
                <md-input v-model="val"></md-input>
            </md-field>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            'value': Object,
            'keyName': {
                type: String,
                'default': 'key'
            },
            'valueName': {
                type: String,
                'default': 'value'
            },
            'keyLabel': {
                type: String,
                'default': 'Key'
            },
            'valueLabel': {
                type: String,
                'default': 'Value'
            }
        },
        computed: {
            key: {
                get() {
                    return this.value[this.keyName]
                },
                set(value) {
                    const newValue = {};
                    newValue[this.keyName] = value;
                    newValue[this.valueName] = this.val;

                    this.$emit('input', newValue);
                }
            },
            val: {
                get() {
                    return this.value[this.valueName];
                },
                set(value) {
                    const newValue = {};
                    newValue[this.keyName] = this.key;
                    newValue[this.valueName] = value;

                    this.$emit('input', newValue);
                }
            }
        }
    }
</script>
