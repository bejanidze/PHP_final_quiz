import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'


Vue.use(Vuex);

export default new Vuex.Store({
  state: {
      status: '',
      token: localStorage.getItem('access_token') || '',
      user : JSON.parse(localStorage.getItem('user')) || {}
  },
  mutations: {
      auth_request(state){
          state.status = 'loading'
      },
      auth_success(state, token, user){
          state.status = 'success';
          state.token = token;
          state.user = JSON.parse(localStorage.getItem('user')) || {};
      },
      auth_error(state){
          state.status = 'error'
      },
      logout(state){
          state.status = '';
          state.token = ''
          state.user = {}
      },
  },
  actions: {
      login({commit}, user){
          return new Promise((resolve, reject) => {
              commit('auth_request');
              axios({url: '/auth/signin', data: user, method: 'POST' })
                  .then(resp => {
                      const token = resp.data.access_token;
                      const user = resp.data.user;
                      localStorage.setItem('access_token', token);
                      localStorage.setItem('user', JSON.stringify(user));
                      axios.defaults.headers.common['Authorization'] = token;
                      commit('auth_success', token, user);
                      resolve(resp)
                  })
                  .catch(err => {
                      commit('auth_error');
                      localStorage.removeItem('access_token');
                      reject(err)
                  })
          })
      },
      register({commit}, user){
          return new Promise((resolve, reject) => {
              commit('auth_request');
              axios({url: '/auth/signup', data: user, method: 'POST' })
                  .then(resp => {
                      resolve(resp)
                  })
                  .catch(err => {
                      commit('auth_error', err);
                      localStorage.removeItem('access_token');
                      reject(err)
                  })
          })
      },
      logout({commit}){
          return new Promise((resolve, reject) => {
              commit('logout');
              localStorage.removeItem('access_token');
              localStorage.removeItem('user');
              delete axios.defaults.headers.common['Authorization'];
              resolve()
          })
      }
  },
  getters : {
      isLoggedIn: state => !!state.token,
      authStatus: state => state.status,
  },
  modules: {
  }
})
