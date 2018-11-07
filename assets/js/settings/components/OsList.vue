<template>
    <div class="d-dockerfile-list">
        <div v-if="loading">Loading list...</div>
        <d-table v-else :headers="headers" :rows="osList" :new-row="currentRow" entity-name="Operating System"
                 @addNewRow="addNewOs" @editItem="edit" @deleteSelected="deleteItems">
            <template slot="addNewDialog" v-if="currentRow">
                <md-dialog-title v-if="!currentRow.id">Add new Operating System <br /><span v-show="newRowId">[{{ newRowId }}]</span></md-dialog-title>
                <md-dialog-title v-else>Edit Operating System</md-dialog-title>
                <md-dialog-content style="min-width: 500px;">
                    <div v-if="!saving">
                        <div class="md-layout md-gutter" v-if="!currentRow.id">
                            <div class="md-layout-item">
                                <md-field :class="getValidationClass('name')">
                                    <label>Name</label>
                                    <md-input v-model="currentRow.name" />
                                    <span class="md-error" v-if="errors.name">{{ errors.name }}</span>
                                    <span class="md-error" v-if="!$v.currentRow.name.required">Name is required.</span>
                                </md-field>
                            </div>
                            <div class="md-layout-item">
                                <md-field :class="getValidationClass('name')">
                                    <label>Version</label>
                                    <md-input v-model="currentRow.version" />
                                    <span class="md-error" v-if="errors.version">{{ errors.version }}</span>
                                    <span class="md-error" v-if="!$v.currentRow.version.required">Version is required.</span>
                                </md-field>
                            </div>
                        </div>
                        <div v-else class="md-layout md-gutter">
                            <md-field>
                                <label>ID</label>
                                <md-input :disabled="true" v-model="currentRow.id" />
                            </md-field>
                        </div>
                        <div class="md-layout md-gutter">
                            <md-field :class="{'md-invalid': errors.description}">
                                <label>Description</label>
                                <md-textarea v-model="currentRow.description"></md-textarea>
                                <span class="md-error" v-if="errors.description">{{ errors.description }}</span>
                            </md-field>
                        </div>
                        <md-chips v-model="currentRow.images" :md-check-duplicated="true">
                            <label>Images</label>
                            <div class="md-helper-text">If added image is already in other OS it will be removed.</div>
                        </md-chips>
                    </div>
                    <div v-if="saving">
                        Saving changes, please wait...
                    </div>
                </md-dialog-content>
                <md-dialog-actions v-if="!saving">
                    <md-button class="md-primary" @click="closeDialog">Cancel</md-button>
                    <md-button class="md-primary" @click="save">Save</md-button>
                </md-dialog-actions>
            </template>
        </d-table>
    </div>
</template>

<script>
    import _ from 'lodash';
    import { validationMixin } from 'vuelidate';
    import { required } from 'vuelidate/lib/validators';
    import { Inflector } from "../../lib/inflector";
    import Api from '../../api/os';

    export default {
        mixins: [validationMixin],
        data() {
            return {
                loading: true,
                saving: false,
                headers: [
                    {id: 'id', label: 'ID'},
                    {id: 'description', label: 'Description'},
                    {id: 'images', label: 'Docker images'}
                ],
                currentRow: null,
                osList: [],
                errors: {}
            }
        },
        methods: {
            getValidationClass(fieldName) {
                const field = this.$v.currentRow[fieldName];

                return {
                    'md-invalid': this.errors[fieldName] || (field.$invalid && field.$dirty)
                };
            },
            clearForm() {
                this.currentRow = {
                    name: null,
                    version: null,
                    description: null,
                    images: []
                };
                this.$v.$reset();
            },
            addNewOs() {
                this.clearForm();
            },
            closeDialog() {
                this.currentRow = null;
            },
            isValidForm() {
                this.$v.$touch();

                return !this.$v.$invalid;
            },
            save() {
                if (!this.isValidForm()) {
                    return;
                }

                if (this.currentRow.id) {
                    this.updateOs();
                } else {
                    this.createNewOs();
                }
            },
            edit(item) {
                this.currentRow = JSON.parse(JSON.stringify(item));
            },
            deleteItems(items) {
                this.loading = true;
                Api.delete(_.map(items, 'id')).finally(() => {
                    this.loadList();
                });
            },
            createNewOs() {
                this.saving = true;
                Api.create(
                    this.currentRow.name,
                    this.currentRow.version,
                    _.trim(`${this.currentRow.name} ${this.currentRow.version}. ${this.currentRow.description || ''}`),
                    this.currentRow.images
                ).then((response) => {
                    this.osList.push(response.data);
                    this.closeDialog();
                }).catch((response) => {
                    if (response.hasOwnProperty('errors')) {
                        this.errors = response.errors;
                    }
                }).finally(() => {
                    this.saving = false;
                });
            },
            updateOs() {
                this.saving = true;
                Api.update(this.currentRow.id, this.currentRow.description, this.currentRow.images).then((response) => {
                    const index = _.findIndex(this.osList, {id: this.currentRow.id});
                    this.osList.splice(index, 1, response.data);
                    this.closeDialog();
                }).catch((response) => {
                    if (response.hasOwnProperty('errors')) {
                        this.errors = response.errors;
                    }
                }).finally(() => {
                    this.saving = false;
                });
            },
            loadList() {
                this.loading = true;
                Api.list().then((list) => {
                    this.osList = list;
                    this.loading = false;
                });
            }
        },
        computed: {
            addingNewRow() {
                return this.currentRow !== null;
            },
            newRowId() {
                if (!this.currentRow) {
                    return null;
                }

                let id = Inflector.toNormalizedHyphenCase(this.currentRow.name || '');
                if (this.currentRow.version) {
                    id += '-' + Inflector.toNormalizedHyphenCase(this.currentRow.version || '');
                }

                return id;
            }
        },
        created() {
            this.loadList();
        },
        validations() {
            if (!this.currentRow || this.currentRow.id) {
                return {};
            } else {
                return {
                    currentRow: {
                        name: {
                            required
                        },
                        version: {
                            required
                        }
                    }
                }
            }
        }
    }
</script>

