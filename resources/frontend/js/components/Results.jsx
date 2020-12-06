import React from 'react';
import styled from "styled-components";
import Moment from "react-moment";
import _ from "lodash";

const ResultBlock = styled.form`
    margin-bottom: 20px;
`;

const Ul = styled.form`
    margin: 0;
`;

export default ({url, errors}) => {
    return (
        <div className="card-body" style={{paddingTop: 0}}>
            {!_.isEmpty(errors) && <div className="alert alert-danger" role="alert">
                <Ul>
                    {Object.keys(errors).map(key => (<li key={key}>{errors[key][0]}</li>))}
                </Ul>
            </div>}
            {url.short_link && (
                <ResultBlock>
                    <h4>Short link:</h4>
                    <a href={url.short_link}>{url.short_link}</a>
                </ResultBlock>
            )}
            {url.expires_at && (
                <ResultBlock>
                    <h4>Expires at:</h4>
                    <Moment format="DD/MM/YYYY">
                        {url.expires_at}
                    </Moment>
                </ResultBlock>
            )}
        </div>
    );
}
