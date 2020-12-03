import React from 'react';
import styled from "styled-components";
import Moment from "react-moment";

const ResultBlock = styled.form`
    margin-bottom: 20px;
`;

export default (props) => {
    return (
        <div className="card-body">
            {props.short_link && (
                <ResultBlock>
                    <h4>Short link:</h4>
                    <a href={props.short_link}>{props.short_link}</a>
                </ResultBlock>
            )}
            {props.expires_at && (
                <ResultBlock>
                    <h4>Expires at:</h4>
                    <Moment format="DD/MM/YYYY">
                        {props.expires_at}
                    </Moment>
                </ResultBlock>
            )}
        </div>
    );
}
