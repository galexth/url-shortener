import axios from 'axios';

axios.defaults.withCredentials = true;

const API_HOST = process.env.MIX_APP_URL + '/api';

export default {
    index(data) {
        return axios.post(`${API_HOST}/urls/search`, data);
    },
    destroy(id) {
        return axios.delete(`${API_HOST}/urls/${id}`);
    },
};
