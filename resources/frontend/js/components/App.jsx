import React, {useState} from 'react';
import Form from './Form';
import api from '../api';
import { NotificationManager, NotificationContainer } from 'react-notifications';
import Results from "./Results";

import 'react-notifications/lib/notifications.css';

function App() {

    const [url, setUrl] = useState({});
    const [loading, setLoading] = useState(false);
    const [errors, setErrors] = useState({});

    const submit = (e) => {
        e.preventDefault();

        let urlValue = e.target.url.value;

        if (urlValue && ! urlValue.match(/^https?:\/\//i)) {
            urlValue = 'http://' + urlValue;
        }

        setLoading(true);
        setErrors({});
        setUrl({});

        api.store({
            url: urlValue,
            expires_at: e.target.expires_at.value || null,
        })
            .then(({data}) => setUrl(data))
            .catch(err => {
                if (err.response.status === 422) {
                    setErrors(err.response.data.errors);
                    NotificationManager.error(err.response.data.message);
                    return false;
                }

                NotificationManager.error('Something wrong')
            })
            .finally(() => setLoading(false));

        return false;
    }

    return (
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-md-8">
                    <div className="card">
                        <div className="card-header">URL Shortener</div>
                        <div className="card-body">
                            <Form onSubmit={submit}/>
                            {loading && <span
                                className="spinner-border spinner-border-lg"
                                role="status"
                                aria-hidden="true"
                            ></span>}
                        </div>
                        <Results url={url} errors={errors}/>
                    </div>
                </div>
            </div>
            <NotificationContainer/>
        </div>
    );
}

export default App;
