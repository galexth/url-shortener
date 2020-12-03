import axios from 'axios';

export const HOST = 'http://localhost:8000/api';
axios.defaults.withCredentials = true;

export default {
    index() {
        return axios.get(`${HOST}/urls`);
    },
    destroy(id) {
        return axios.delete(`${HOST}/urls/${id}`);
    },
    store(data) {
        return axios.post(`${HOST}/urls`, data);
    },
};
