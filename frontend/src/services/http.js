import axios from 'axios';
import settings from './settings';
import ls from "local-storage";

/* Por padrão as operações irão buscar a api rest */
axios.defaults.baseURL = settings.restapi;

axios.interceptors.request.use(config => {
    config.headers.Authorization = `${ls('token')}`;

    return config;
});

axios.interceptors.response.use(
    response => {
        return response;
    },
    error => {

        // Verifica se o "token" do servidor está vencido e se estiver remove o token local.
        if (parseInt(error.response.status, 10) === 401) {
            localStorage.removeItem('token');
            window.location.reload(true);
        }

        return Promise.reject(error);
    }
);

const http = {
    post: (url, data) => axios.post(url, data),
    put: (url, data) => axios.put(url, data),
    get: async function(url) {
            //let response = await fetch(settings.restapi + url);
            //let json = await response.json();
            let response = await axios.get(url);
            let json = response.data;

            return json;
        },
    delete: (url, data) => axios.delete(url, data),
    getDropzoneConfig: (url, method, options) =>
        Object.assign(options, {
            url: url,
            method
        })
};

export default http;