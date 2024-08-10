import './assets/main.css';

import { createApp } from 'vue';
import App from './App.vue';
import { router } from './router/index';

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'bootstrap/dist/js/bootstrap.bundle.js';

const app = createApp(App);

app.use(router);

app.mount('#app');
