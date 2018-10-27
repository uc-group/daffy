import Vue from 'vue';
import './md';
import '../css/app.scss';
import './icons';
import './dockerfile';

new Vue({
    el: '#app',
    data() {
        return {
            menuVisible: false
        }
    },
    methods: {
        toggleMenu() {
            this.menuVisible = !this.menuVisible;
        }
    }
});
