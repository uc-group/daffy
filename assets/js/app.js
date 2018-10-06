require('../css/app.scss');
require.context('../svg/', true, /\.svg$/);

import Vue from 'vue';
import TestComponent from './components/TestComponent';

new Vue({
    el: '#app',
    components: {
        test: TestComponent
    }
});
