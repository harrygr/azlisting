global.Vue = require('vue');

require('vue-resource');
Vue.use(require('vue-chunk'));

Vue.config.debug = true;


global.vm = new Vue({
    el: '#app',

    components: {
        'az-programme-list': require('./components/az-programme-list.vue'),
    },

    data: {
        thing: "hello world"
    }

});