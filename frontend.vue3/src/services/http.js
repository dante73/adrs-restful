import axios from 'axios';
import settings from './settings';
import ls from "local-storage";

/* Por padrão as operações irão buscar a api rest */
axios.defaults.baseURL = settings.restapi;

axios.interceptors.request.use(config => {
        // Attach jwt token
        config.headers.Authorization = `${ls('token')}`;

        config.baseURL = settings.backend;
    /* Alguns dados e operações serão feitos pelo backend com nodejs. */
    /*
    if (config.url.match(/(\/api\/)?(login|estado|regiao|cidade)\/?/)) {
    }
    else {
        if (
            config.method === 'post' ||
            config.method === 'put'
        ) {
            config.headers['Content-Type'] = 'multipart/form-data;';
        }

    }
     */
    return config;
});

axios.interceptors.response.use(
    response => {
        return response;
    },
    error => {
        return Promise.reject(error);
    }
);

const http = {
    post: (url, data) => axios.post(url, data),
    put: (url, data) => axios.put(url, data),
    get: url => axios.get(url),
    delete: (url, data) => axios.delete(url, data),
    getDropzoneConfig: (url, method, options) =>
        Object.assign(options, {
            url: url,
            method
        })
};

export default http;