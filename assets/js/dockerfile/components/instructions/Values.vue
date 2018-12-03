<template>
    <div>
        <div class="md-layout md-gutter" :key="index" v-for="(row, index) in rows">
            <div class="md-layout-item">
                <md-field>
                    <label>Key {{ index + 1 }}</label>
                    <md-input v-model="rows[index].key" @input="update"></md-input>
                </md-field>
            </div>
            <div class="md-layout-item">
                <md-field>
                    <label>Value {{ index + 1 }}</label>
                    <md-input v-model="rows[index].value" @input="update"></md-input>
                </md-field>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            'value': Object,
            'propertyName': {
                type: String,
                'default': 'values'
            }
        },
        data() {
            return {
                rows: []
            }
        },
        created() {
            this.makeRows();
        },
        methods: {
            makeRows() {
                this.rows = [..._.map(this.value[this.propertyName] || {}, (value, key) => {
                    return {
                        key: key,
                        value: value
                    }
                }), { key: null, value: null }];
            },
            update() {
                const values = {};
                values[this.propertyName] = {};
                _.each(this.rows, (row) => {
                    if (row.key) {
                        const key = values[this.propertyName].hasOwnProperty(row.key) ? `~${row.key}` : row.key;
                        values[this.propertyName][key] = row.value;
                    }
                });
                this.$emit('input', values);
            }
        },
        watch: {
            value(to) {
                this.makeRows();
            }
        }
    }
</script>
