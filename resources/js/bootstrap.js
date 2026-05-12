import axios from 'axios';
import csrf from './csrf';

csrf.init();

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
