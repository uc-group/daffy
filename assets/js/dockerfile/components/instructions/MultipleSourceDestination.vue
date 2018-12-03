<template>
    <div class="md-layout md-gutter">
        <div class="md-layout-item">
            <md-field :key="index" v-for="(source, index) in localSources" md-clearable>
                <label>Source {{ (index + 1) }}</label>
                <md-input v-model="localSources[index]" @input="updateSources"></md-input>
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
        data() {
            return {
                localSources: [...this.value.sources || [], null]
            }
        },
        computed: {
            sources() {
                return _.filter(this.localSources, (element) => {
                    return !!element;
                });
            },
            destination: {
                get() {
                    return this.value.destination
                },
                set(value) {
                    const newValue = _.clone(this.value);
                    newValue.destination = value;
                    this.$emit('input', newValue);
                }
            }
        },
        methods: {
            updateSources() {
                const newValue = _.clone(this.value);
                newValue.sources = this.sources;
                this.$emit('input', newValue);
            }
        },
        watch: {
            value(to) {
                this.localSources = [...to.sources || [], null];
            }
        }
    }
</script>
