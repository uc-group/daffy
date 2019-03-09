import Vue from 'vue';
import Vuex from 'vuex';
import dockerCompose from './modules/dockerCompose'

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    modules: {
        dockerCompose
    },
    strict: debug
});