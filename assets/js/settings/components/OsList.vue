<template>
    <div class="d-dockerfile-list">
        <div v-if="loading">Loading list...</div>
        <d-table v-else :headers="headers" :rows="osList" :new-row="newRow" @addNewRow="addNewOs">
            <template slot="addNewDialog">
                <md-dialog-title>Add new Operating System <br /><span v-show="newRowId">[{{ newRowId }}]</span></md-dialog-title>
                <md-dialog-content v-if="newRow" style="min-width: 500px;">
                    <div v-if="!saving">
                        <div class="md-layout md-gutter">
                            <div class="md-layout-item">
                                <md-field>
                                    <label>Name</label>
                                    <md-input v-model="newRow.name"></md-input>
                                </md-field>
                            </div>
                            <div class="md-layout-item">
                                <md-field>
                                    <label>Version</label>
                                    <md-input v-model="newRow.version"></md-input>
                                </md-field>
                            </div>
                        </div>
                        <md-field>
                            <label>Description</label>
                            <md-textarea v-model="newRow.description"></md-textarea>
                        </md-field>
                    </div>
                    <div v-if="saving">
                        Saving changes, please wait...
                    </div>
                </md-dialog-content>
                <md-dialog-actions v-if="!saving">
                    <md-button class="md-primary" @click="cancelNewRow">Cancel</md-button>
                    <md-button class="md-primary" @click="save">Save</md-button>
                </md-dialog-actions>
            </template>
        </d-table>
    </div>
</template>

<script>
    import _ from 'lodash';
    import { Inflector } from "../../lib/inflector";
    import Api from '../../api/os';

    export default {
        data() {
            return {
                loading: true,
                saving: false,
                headers: [
                    {id: 'id', label: 'ID'},
                    {id: 'description', label: 'Description'},
                    {id: 'images', label: 'Docker images'}
                ],
                newRow: null,
                osList: []
            }
        },
        methods: {
            addNewOs() {

                this.newRow = {
                    name: null,
                    version: null,
                    description: null,
                    images: []
                }
            },
            cancelNewRow() {
                this.newRow = null;
            },
            save() {
                this.saving = true;
                Api.create(
                    this.newRow.name,
                    this.newRow.version,
                    _.trim(`${this.newRow.name} ${this.newRow.version}. ${this.newRow.description || ''}`),
                    []
                ).then((response) => {
                    this.osList.push(response.data);
                    this.cancelNewRow();
                }).finally(() => {
                    this.saving = false;
                });
            }
        },
        computed: {
            addingNewRow() {
                return this.newRow !== null;
            },
            newRowId() {
                if (!this.newRow) {
                    return null;
                }

                let id = Inflector.toNormalizedHyphenCase(this.newRow.name || '');
                if (this.newRow.version) {
                    id += '_' + Inflector.toNormalizedHyphenCase(this.newRow.version || '');
                }

                return id;
            }
        },
        created() {
            this.loading = true;
            Api.list().then((list) => {
                this.osList = list;
                this.loading = false;
            })
        }
    }
</script>

