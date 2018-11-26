<template>
    <div>
        <div class="md-layout dockerfile">
            <md-list class="md-layout-item dockerfile__details">
                <md-subheader>Operating system ({{ os.id }})</md-subheader>
                <md-list-item>
                    <span class="md-list-item-text">{{ os.description }}</span>
                </md-list-item>
                <md-list-item>
                    <span class="md-list-item-text"><strong>Package manager:</strong> {{ os.packageManager }}</span>
                </md-list-item>
                <md-list-item class="dockerfile__preview">
                    <pre><code v-html="dockerfile" style="white-space: normal;"></code></pre>
                </md-list-item>
            </md-list>
            <div class="md-layout-item dockerfile__layers" style="position: relative;">
                <div class="md-layout md-gutter">
                    <div class="md-layout-item">
                        <md-field>
                            <label>Layer type</label>
                            <md-select v-model="newLayer">
                                <md-option v-for="(label, id) in availableLayers" :key="id" :value="id">{{ label }}</md-option>
                            </md-select>
                        </md-field>
                    </div>
                    <div class="md-layout-item">
                        <md-button class="md-raised md-primary" @click="addLayer" :disabled="!newLayer">Add selected layer</md-button>
                    </div>
                </div>
                <draggable v-model="layers" @end="save" :options="{handle: '.md-card-move'}">
                    <md-card class="dockerfile-layer" v-for="layer in layers" :key="layer.id" md-with-hover ref="layers">
                        <md-card-header class="md-card-move md-layout md-alignment-top-space-between">
                            <div class="md-title">
                                {{ label(layer.data.type) }}
                            </div>
                            <div>
                                <md-button class="md-icon-button" @click="removeLayer(layer)">
                                    <md-icon>delete</md-icon>
                                </md-button>
                            </div>
                        </md-card-header>
                        <md-card-expand>
                            <md-card-actions md-alignment="space-between">
                                <div v-html="teaser(layer.data)"></div>
                                <md-card-expand-trigger ref="expandButton">
                                    <md-button class="md-icon-button">
                                        <md-icon>keyboard_arrow_down</md-icon>
                                    </md-button>
                                </md-card-expand-trigger>
                            </md-card-actions>

                            <md-card-expand-content>
                                <md-card-content>
                                    <component :is="getComponent(layer.data.type)" v-model="layer.data.args" @input="save"></component>
                                </md-card-content>
                            </md-card-expand-content>
                        </md-card-expand>
                    </md-card>
                </draggable>
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
        props: ['config', 'os'],
        components: { Draggable },
        data() {
            return {
                saving: false,
                dockerfile: null,
                layers: _.map(this.config.layers, (layer) => {
                    return {
                        id: uuid(),
                        data: layer,
                        expanded: false
                    };
                }),
                newLayer: null
            }
        },
        created() {
            Api.build(this.config.id).then((data) => {
                this.dockerfile = data;
            });
        },
        computed: {
            currentLayerTypes() {
                return _.uniq(_.map(this.layers, (layer) => {
                    return layer.data.type;
                }));
            },
            availableLayers() {
                const layers = {};
                _.each(layerConfig, (config, type) => {
                    if (!config.multiple && this.currentLayerTypes.indexOf(type) !== -1) {
                        return true;
                    }

                    layers[type] = config.label;
                });

                return layers;
            }
        },
        methods: {
            onSort(event) {

            },
            label(type) {
                return (layerConfig[type] || {label: type}).label;
            },
            removeLayer(layer) {
                const index = this.layers.indexOf(layer);
                this.layers.splice(index, 1);
            },
            getComponent(type) {
                return (layerConfig[type] || {}).component;
            },
            addLayer() {
                if (!this.newLayer || !layerConfig.hasOwnProperty(this.newLayer)) {
                    return;
                }

                const layer = {
                    id: uuid(),
                    type: this.newLayer,
                    args: (layerConfig[this.newLayer].defaultArgs || {defaultArgs() {return {}}})()
                };

                this.newLayer = null;
                this.layers.push({
                    data: layer,
                    expanded: true
                });

                this.$nextTick(() => {
                    const last = this.$refs.layers[this.$refs.layers.length - 1];
                    if (last) {
                        last.$el.querySelector('.md-card-expand-trigger').click();
                        window.scrollTo(0, last.$el.getBoundingClientRect().top);
                    }
                })
            },
            save: _.debounce(function () {
                this.saving = true;
                Api.save(this.config.id, {
                    layersConfig: _.map(this.layers, 'data')
                }).then((data) => {
                    this.dockerfile = data;
                }).finally(() => {
                    this.saving = false;
                })
            }, 1000),
            teaser(layer) {
                if (!layerConfig.hasOwnProperty(layer.type)) {
                    return null;
                }

                return (layerConfig[layer.type].teaser || {teaser() {return '&nbsp;'}})(layer);
            }
        }
    }
</script>
