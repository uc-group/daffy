<template>
    <div class="d-dockerfile-list">
        <md-table>
            <md-table-row>
                <md-table-head>Name</md-table-head>
                <md-table-head>Description</md-table-head>
            </md-table-row>
            <md-table-row v-for="file in files">
                <md-table-cell>{{ file.name }}</md-table-cell>
                <md-table-cell>{{ file.description }}</md-table-cell>
            </md-table-row>
        </md-table>
        <md-speed-dial class="md-bottom-right" v-if="!newRow">
            <md-speed-dial-target @click="addNewRow">
                <md-icon>add</md-icon>
            </md-speed-dial-target>
        </md-speed-dial>
        <md-dialog :md-active.sync="addingNewRow" :md-close-on-esc="false" :md-click-outside-to-close="false">
            <md-dialog-title>Add new dockerfile</md-dialog-title>
            <md-dialog-content v-if="newRow" style="min-width: 500px;">
                <md-field>
                    <label>Name</label>
                    <md-input v-model="newRow.name"></md-input>
                </md-field>
                <md-field>
                    <label>Description</label>
                    <md-textarea v-model="newRow.description"></md-textarea>
                </md-field>
            </md-dialog-content>
            <md-dialog-actions>
                <md-button class="md-primary" @click="cancelNewRow">Cancel</md-button>
                <md-button class="md-primary" @click="save">Save</md-button>
            </md-dialog-actions>
        </md-dialog>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                newRow: null,
                files: [
                    {name: 'test1', description: 'Lipsum dolor.'},
                    {name: 'test2', description: 'Sit amet.'}
                ]
            }
        },
        methods: {
            addNewRow() {
                this.newRow = {
                    name: null,
                    description: null
                }
            },
            cancelNewRow() {
                this.newRow = null;
            },
            save() {
                this.files.push(this.newRow);
                this.cancelNewRow();
            }
        },
        computed: {
            addingNewRow() {
                return this.newRow !== null;
            }
        }
    }
</script>

