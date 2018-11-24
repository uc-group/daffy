<template>
    <div class="d-dockerfile-list">
        <md-table>
            <md-table-row>
                <md-table-head>Name</md-table-head>
                <md-table-head>Base Image</md-table-head>
                <md-table-head>Description</md-table-head>
            </md-table-row>
            <md-table-row :key="file.name" v-for="file in files" v-show="!file.removed">
                <md-table-cell>{{ file.name }}</md-table-cell>
                <md-table-cell>{{ file.baseImage }}</md-table-cell>
                <md-table-cell>{{ file.description }}</md-table-cell>
                <md-table-cell>
                    <md-button class="md-icon-button" :href="viewUrl(file)">
                        <md-icon>edit</md-icon>
                    </md-button>
                    <md-button class="md-icon-button" @click="remove(file)">
                        <md-icon>delete</md-icon>
                    </md-button>
                </md-table-cell>
            </md-table-row>
        </md-table>
        <div v-show="loading">Loading list...</div>
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
                <md-autocomplete v-model="newRow.baseImage" :md-options="images">
                    <label>Base image</label>
                    <template slot="md-autocomplete-item" slot-scope="{ item, term }">
                        <md-highlight-text :md-term="term">{{ item }}</md-highlight-text>
                    </template>
                    <template slot="md-autocomplete-empty" slot-scope="{ term }">
                        No images "{{ term }}" were found.
                    </template>
                </md-autocomplete>
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
    import Api from '../../api/dockerfile';
    import _ from 'lodash';

    export default {
        name: 'd-dockerfile-list',
        props: {
            images: Array
        },
        data() {
            return {
                loading: true,
                newRow: null,
                files: []
            }
        },
        created() {
            Api.list().then((data) => {
                _.each(data, (dockerfile) => {
                    dockerfile.removed = false;
                    this.files.push(dockerfile);
                });
            }).finally(() => {
                this.loading = false;
            })
        },
        methods: {
            addNewRow() {
                this.newRow = {
                    name: null,
                    description: null,
                    baseImage: null
                }
            },
            cancelNewRow() {
                this.newRow = null;
            },
            save() {
                Api.create(this.newRow.name, this.newRow.baseImage, this.newRow.description).then((data) => {
                    this.files.push(data.data);
                    window.location = Api.viewUrl(data.data.id);
                }).catch(() => {
                    this.cancelNewRow();
                });
            },
            remove(dockerfile) {
                dockerfile.removed = true;
                Api.remove(dockerfile.id).then(() => {
                    const index = this.files.indexOf(dockerfile);
                    if (index !== -1) {
                        this.files.splice(index, 1);
                    }
                }).catch(() => {
                    dockerfile.removed = false;
                })
            },
            viewUrl(dockerfile) {
                return Api.viewUrl(dockerfile.id);
            }
        },
        computed: {
            addingNewRow() {
                return this.newRow !== null;
            }
        }
    }
</script>

