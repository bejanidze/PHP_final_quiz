import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify';
import Axios from 'axios'


Vue.config.productionTip = false;
Axios.interceptors.response.use(function (response) {
    return response
}, function (error) {
    if (error.response.status === 401) {
        store.dispatch('logout')
        router.push('/login')
    }
    return Promise.reject(error)
});
Vue.prototype.$http = Axios;
Vue.prototype.$http.defaults.baseURL= 'http://127.0.0.1:8000/api/';
const token = localStorage.getItem('access_token');
if (token) {
    Vue.prototype.$http.defaults.headers.common['Authorization'] = 'Bearer ' + token;
    Vue.prototype.$http.defaults.headers.common['Content-Type'] = 'application/json';
    Vue.prototype.$http.defaults.headers.common['Accept'] = 'application/json'
}
new Vue({
  router,
  store,
  vuetify,
  render: h => h(App)
}).$mount('#app')
