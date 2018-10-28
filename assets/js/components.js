import Vue from 'vue';

function importComponents(r) {
    r.keys().forEach(name => {
        let componentName = name.replace(/^.*\/|^\.\/|\.vue$/g, '');
        componentName = componentName[0].toLowerCase() + componentName.substr(1);
        componentName = componentName.replace(/([A-Z])/g, '-$1').toLowerCase();

        Vue.component('d-' + componentName, r(name).default);
    })
}

importComponents(require.context('./components', true, /\.vue$/));
