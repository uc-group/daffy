<template>
    <div class="d-dockerfile-list">
        <md-table>
            <md-table-row>
                <md-table-head>Name</md-table-head>
                <md-table-head>Description</md-table-head>
            </md-table-row>
            <md-table-row :key="file.name" v-for="file in files" v-show="!file.removed">
                <md-table-cell>{{ file.name }}</md-table-cell>
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
        <md-speed-dial class="md-bottom-right" v-if="!newDockerfileModal">
            <md-speed-dial-target @click="addNewRow">
                <md-icon>add</md-icon>
            </md-speed-dial-target>
        </md-speed-dial>
        <d-new-dockerfile-modal :images="images" :open.sync="newDockerfileModal" @create="save"></d-new-dockerfile-modal>
    </div>
</template>

<script>
    import Api from '../../api/dockerfile';
    import NewDockerfile from './modals/NewDockerfile';
    import _ from 'lodash';

    export default {
        name: 'd-dockerfile-list',
        components: {
            DNewDockerfileModal: NewDockerfile
        },
        props: {
            images: Array
        },
        data() {
            return {
                newDockerfileModal: false,
                loading: true,
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
                this.newDockerfileModal = true;
            },
            save(newDockerfile) {
                Api.create(newDockerfile).then((data) => {
                    this.files.push(data.data);
                    window.location = Api.viewUrl(data.data.id);
                }).catch(() => {
                    this.newDockerfileModal = false;
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
        }
    }
</script>

