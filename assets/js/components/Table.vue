<template>
    <div class="d-table">
        <md-table md-fixed-header v-model="rows" style="margin-bottom: 100px;" :md-selected-value.sync="localSelected">
            <md-table-toolbar slot="md-table-alternate-header" slot-scope="{ count }">
                <div class="md-toolbar-section-start">{{ getAlternateLabel(count) }}</div>

                <div class="md-toolbar-section-end">
                    <md-button class="md-icon-button" @click="$emit('deleteSelected', localSelected)">
                        <md-icon>delete</md-icon>
                    </md-button>
                </div>
            </md-table-toolbar>
            <md-table-row slot="md-table-row" slot-scope="{ item }" md-selectable="multiple" md-auto-select>
                <md-table-cell :md-label="header.label" v-for="header in headers" :key="header.id">{{ item[header.id] || null }}</md-table-cell>
                <md-table-cell>
                    <md-button class="md-icon-button" @click="edit(item)">
                        <md-icon>edit</md-icon>
                    </md-button>
                </md-table-cell>
            </md-table-row>
            <md-table-empty-state :md-label="`No ${entityName}s found.`">
                <md-button class="md-primary md-raised" @click="addNewRow">Create New {{ entityName }}</md-button>
            </md-table-empty-state>
        </md-table>
        <md-speed-dial class="md-bottom-right" v-if="!newRow && rows.length">
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
            entityName: {
                type: String,
                default: 'Item'
            },
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
        data() {
            return {
                localSelected: []
            }
        },
        methods: {
            addNewRow() {
                this.$emit('addNewRow');
            },
            edit(item) {
                this.$emit('editItem', item);
                this.localSelected = [];
            },
            getAlternateLabel (count) {
                let plural = '';

                if (count > 1) {
                    plural = 's';
                }

                return `${count} item${plural} selected`;
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

