<template>
    <div>
        <div class="md-layout dockerfile">
            <md-list class="md-layout-item md-size-20" style="border: 1px solid #eee;">
                <md-list-item v-for="stage in stages" :key="stage.alias">
                    <md-icon>layers</md-icon>
                    <a href="#" class="md-list-item-link">{{ stage.alias }}</a>
                </md-list-item>
            </md-list>
            <div style="margin-left: 30px;">
                <pre>{{ dockerfile }}</pre>
            </div>
        </div>
        <md-snackbar md-position="left" :md-active="saving" md-persistent>
            <span>Saving changes...</span>
        </md-snackbar>
    </div>
</template>

<script>
    import Api from '../../api/dockerfile';
    import layerConfig from '../layer-config';
    import Draggable from 'vuedraggable';
    import uuid from 'uuid/v4';

    export default {
        name: 'd-dockerfile',
        props: {
            config: Object,
            stages: Array
        },
        components: { Draggable },
        data() {
            return {
                saving: false,
                dockerfile: null
            }
        },
        created() {
            Api.build(this.config.id).then((data) => {
                this.dockerfile = data;
            });
        },
        methods: {
        }
    }
</script>
