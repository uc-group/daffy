import Vue from 'vue';
import store from './store';
import './md';
import '../css/app.scss';
import './icons';
import './components';
import './dockerfile';
import './dockercompose';
import './settings';

new Vue({
    el: '#app',
    store,
    data() {
        return {
            menuVisible: false,
            settingsExpand: false,
            dockerExpand: false
        }
    },
    methods: {
        toggleMenu() {
            this.menuVisible = !this.menuVisible;
        }
    },
    watch: {
        settingsExpand(to) {
            if (to) {
                this.menuVisible = true;
            }
        },
        dockerExpand(to) {
            if (to) {
                this.menuVisible = true;
            }
        }
    }
});
