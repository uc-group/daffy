<template>
    <md-dialog :md-active.sync="open" :md-close-on-esc="false" :md-click-outside-to-close="false">
        <md-dialog-title>Add new dockerfile</md-dialog-title>
        <md-dialog-content v-if="open" style="min-width: 500px;">
            <md-field>
                <label>Name</label>
                <md-input v-model="name"></md-input>
            </md-field>
            <md-autocomplete v-model="baseImage" :md-options="images">
                <label>Base image</label>
                <template slot="md-autocomplete-item" slot-scope="{ item, term }">
                    <md-highlight-text :md-term="term">{{ item }}</md-highlight-text>
                </template>
                <template slot="md-autocomplete-empty" slot-scope="{ term }">
                    No images "{{ term }}" were found.
                </template>
            </md-autocomplete>
            <md-field>
                <label>Alias</label>
                <md-input v-model="alias"></md-input>
            </md-field>
            <md-field>
                <label>Description</label>
                <md-textarea v-model="description"></md-textarea>
            </md-field>
        </md-dialog-content>
        <md-dialog-actions>
            <md-button class="md-primary" @click="cancelNewRow">Cancel</md-button>
            <md-button class="md-primary" @click="save">Save</md-button>
        </md-dialog-actions>
    </md-dialog>
</template>

<script>
    export default {
        props: {
            images: Array,
            open: Boolean
        },
        data() {
            return {
                name: null,
                baseImage: null,
                alias: null,
                description: null
            }
        },
        methods: {
            save() {
                this.$emit('create', {
                    name: this.name,
                    baseImage: this.baseImage,
                    alias: this.alias,
                    description: this.description
                });
            },
            cancelNewRow() {
                this.name = null;
                this.baseImage = null;
                this.alias = null;
                this.description = null;
                this.$emit('update:open', false);
            }
        }
    }
</script>
