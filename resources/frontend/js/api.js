import axios from 'axios';

axios.defaults.withCredentials = true;

const API_HOST = process.env.MIX_APP_URL + '/api';

export default {
    store(data) {
        return axios.post(`${API_HOST}/urls`, data);
    },
};
