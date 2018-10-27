require.context('../svg/', true, /\.svg$/);
import Vue from 'vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faDocker } from '@fortawesome/free-brands-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faDocker);

Vue.component('fa-icon', FontAwesomeIcon);
