import Vue from 'vue'
import Vuex from 'vuex'
import axios from '../main'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
  },
  getters: {
  },
  mutations: {
    setConnectionData(state, userData) {
        //state.connection = userData;
        //state.lang = userData.user.lang;
        localStorage.setItem('connection', JSON.stringify(userData));
        axios.defaults.headers.common.Authorization = `Bearer ${userData.access_token}`;
        //window.Echo.connector.options.auth.headers['Authorization'] = `Bearer ${userData.access_token}`;
    },
    clearConnectionData() {
        localStorage.removeItem('connection')
        location.reload()
    },
  },
  actions: {
  },
  modules: {
  }
})
