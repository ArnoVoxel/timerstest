import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

// Import Bootstrap and BootstrapVue CSS files (order is important)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

// axios
import axios from "axios";
//axios.defaults.baseURL = process.env.VUE_APP_API_URL;
Vue.prototype.$axios = axios;
export default axios;

Vue.config.productionTip = false

new Vue({
  router,
  store,
  created()
    {
    const connectionInfo = localStorage.getItem('connection');

        if (connectionInfo) {
            const connectionData = JSON.parse(connectionInfo);
            this.$store.commit('setConnectionData', connectionData);
        }
    },
  render: h => h(App)
}).$mount('#app')

