import 'bootstrap';
import axios from 'axios';

window.axios = axios;

axios.defaults.baseURL = '/api';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
// if (token) axios.defaults.headers.common['X-CSRF-TOKEN'] = token
// axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
