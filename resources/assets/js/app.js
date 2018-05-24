
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import Axios from 'axios';

window.axios = Axios;

window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN': window.Laravel.csrfToken,
    'X-Requested-With': 'XMLHttpRequest'
};

Vue.use(VueRouter);

/**
 *  Import views of application
 */

import AppView from './views/AppView';
import IndexView from './views/IndexView';
import CategoryView from './views/CategoryView';
import LoginView from './views/LoginView';
import RegisterView from './views/RegisterView';
import UserView from  './views/UserView';

/**
 *  Define the routes of application
 */

const router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/', name: 'index', component: IndexView },
        { path: '/category/:id', name: 'category', component: CategoryView, props: true },
        { path: '/login', name: 'login', component: LoginView },
        { path: '/register', name: 'register', component: RegisterView },
        { path: '/user', name: 'user', component: UserView },
    ],
});

/**
 *  Let's init a new Vue instance
 */

const app = new Vue({
    el: '#app',
    created() {
        this.checkAuth();
    },
    data: function () {
        return {
            user: null,
            logged: false,
        }
    },
    components: {
        AppView,
    },
    methods: {
        checkAuth()
        {
            let result;
            axios.get('/api/user')
                .then(response => {
                    this.user = response.data;
                    this.logged = true;

                    result = true;
                })
                .catch(error => {
                    result = false;
                })
                .finally(function () {
                    return result;
                });
        },
    },
    router
});
