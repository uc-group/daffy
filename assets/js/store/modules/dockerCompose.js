import Vue from 'vue';

const state = {
    services: []
};

const getters = {};

const actions = {};

const mutations = {
    addService (state, service) {
        state.services.push(service);
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}