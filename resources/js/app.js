/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router';
window.Vue.use(VueRouter);
window.axios = require('axios');


import PostsIndex from './components/posts/PostsIndex.vue';
import PostsCreate from './components/posts/PostsCreate.vue';
import PostsEdit from './components/posts/PostsEdit.vue';


const routes = [
    {path: '/', components: {postsIndex: PostsIndex}},
    {path: '/create', component: PostsCreate, name: 'createPost'},
    {path: '/edit/:id', component: PostsEdit, name: 'editPost'},
]

const router = new VueRouter({ routes })




/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue'));
// Vue.component('imageselect-component', require('./components/ImageselectComponent.vue'));


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app',
// });

const app = new Vue({ router }).$mount('#app');
