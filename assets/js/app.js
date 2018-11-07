import Vue from 'vue';
import './md';
import '../css/app.scss';
import './icons';
import './components';
import './dockerfile';
import './settings';

new Vue({
    el: '#app',
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
