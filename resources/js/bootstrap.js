import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.timeout = 4000;

const token = localStorage.getItem('auth_token');
if (token) {
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

window.axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (!error.response) {
            console.warn('SERVER OFFLINE / TIDAK ADA INTERNET');
        }

        if (error.response?.status === 401) {
            localStorage.removeItem('auth_token');
        }

        return Promise.reject(error);
    }
);
