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
            <div class="md-layout-item dockerfile__layers">
                <div class="md-layout md-gutter">
                    <div class="md-layout-item">
                        <md-field>
                            <label>Layer type</label>
                            <md-select v-model="newLayer">
                                <md-option value="instruction">Instruction</md-option>
                                <md-option value="php-extension">PHP Extensions</md-option>
                            </md-select>
                        </md-field>
                    </div>
                    <div class="md-layout-item">
                        <md-button class="md-raised md-primary" @click="addLayer" :disabled="!newLayer">Add selected layer</md-button>
                    </div>
                </div>
                <md-card class="dockerfile-layer" v-for="(layer,index) in layers" :key="index" md-with-hover>
                    <md-card-header>
                        <div class="md-title">{{layer.type}}</div>
                    </md-card-header>
                    <md-card-content>
                        <component :is="layer.type" v-model="layer.args" @input="save"></component>
                    </md-card-content>
                </md-card>
            </div>
        </div>
        <md-snackbar md-position="left" :md-active="saving" md-persistent>
            <span>Saving changes...</span>
        </md-snackbar>
    </div>
</template>

<script>
    import Api from '../../api/dockerfile';
    import InstructionLayer from './layers/InstructionLayer';
    import PhpExtensionLayer from './layers/PhpExtensionLayer';

    const defaultArgs = {
        instruction() {
            return {
                instruction: null
            }
        },
        'php-extension'() {
            return {
                extensions: []
            }
        }
    };

    export default {
        name: 'd-dockerfile',
        props: ['config', 'os'],
        components: {
            'instruction': InstructionLayer,
            'php-extension': PhpExtensionLayer
        },
        data() {
            return {
                saving: false,
                dockerfile: null,
                layers: this.config.layers,
                newLayer: null
            }
        },
        created() {
            Api.build(this.config.id).then((data) => {
                this.dockerfile = data;
            });
        },
        methods: {
            addLayer() {
                if (!this.newLayer) {
                    return;
                }

                const layer = {
                    'type': this.newLayer,
                    'args': defaultArgs[this.newLayer]()
                };

                this.newLayer = null;
                this.layers.push(layer);
            },
            save: _.debounce(function () {
                this.saving = true;
                Api.save(this.config.id, {
                    layersConfig: this.layers
                }).then((data) => {
                    this.dockerfile = data;
                }).finally(() => {
                    this.saving = false;
                })
            }, 1000)
        }
    }
</script>
