<template>
    <div class="d-table">
        <md-table md-fixed-header v-model="rows" style="margin-bottom: 100px;">
            <md-table-row  slot="md-table-row" slot-scope="{ item }">
                <md-table-cell :md-label="header.label" v-for="header in headers" :key="header.id">{{ item[header.id] || null }}</md-table-cell>
            </md-table-row>
        </md-table>
        <md-speed-dial class="md-bottom-right" v-if="!newRow">
            <md-speed-dial-target @click="addNewRow">
                <md-icon>add</md-icon>
            </md-speed-dial-target>
        </md-speed-dial>
        <md-dialog :md-active.sync="addingNewRow" :md-close-on-esc="false" :md-click-outside-to-close="false">
            <slot name="addNewDialog"></slot>
        </md-dialog>
    </div>
</template>

<script>
    import _ from 'lodash';

    export default {
        props: {
            headers: {
                type: Array
            },
            rows: {
                type: Array
            },
            idField: {
                type: String,
                default: 'id'
            },
            newRow: {
                type: Object,
                default: null
            }
        },
        methods: {
            addNewRow() {
                this.$emit('addNewRow');
            }
        },
        computed: {
            addingNewRow() {
                return this.newRow !== null;
            },
            fields() {
                return _.map(this.headers, 'id');
            }
        }
    }
</script>

