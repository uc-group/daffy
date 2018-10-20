import Vue from 'vue';
import 'vue-material/dist/vue-material.min.css';
import { MdApp, MdButton, MdContent, MdTabs, MdToolbar, MdDrawer, MdList, MdIcon } from 'vue-material/dist/components'
import '../css/app.scss';
require.context('../svg/', true, /\.svg$/);

Vue.use(MdApp);
Vue.use(MdButton);
Vue.use(MdContent);
Vue.use(MdTabs);
Vue.use(MdToolbar);
Vue.use(MdDrawer);
Vue.use(MdList);
Vue.use(MdIcon);

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
