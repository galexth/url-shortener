import React from 'react';
import Form from './Form';
import api from '../api';
import { NotificationManager, NotificationContainer } from 'react-notifications';
import Results from "./Results";

import 'react-notifications/lib/notifications.css';

class App extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            url: null,
            short_link: null,
            expires_at: null,
            errors: {}
        };

        this.submit = this.submit.bind(this);
    }

    submit(e) {
        e.preventDefault();

        let url = e.target.url.value;

        if (url && ! url.match(/^https?:\/\//)) {
            url = 'http://' + url;
        }

        api.store({
            url: url,
            expires_at: e.target.expires_at.value || null,
        }).then(({data}) => {
            this.setState((prevState) => ({
                url: data.url,
                short_link: data.short_link,
                expires_at: data.expires_at,
            }));
        }).catch(err => {
                if (err.response.status === 422) {
                    Object.keys(err.response.data.errors).forEach((key) => {
                        NotificationManager.error(err.response.data.errors[key][0])
                    });
                    return false;
                }

                NotificationManager.error(err.response.data.error || err.response.data.message || 'Something wrong')
            }
        );

        return false;
    }

    render() {
        return (
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-md-8">
                        <div className="card">
                            <div className="card-header">URL Shortener</div>
                            <div className="card-body">
                                <Form onSubmit={this.submit}/>
                            </div>
                            <Results {...this.state}/>
                        </div>
                    </div>
                </div>
                <NotificationContainer/>
            </div>
        );
    }
}

export default App;
