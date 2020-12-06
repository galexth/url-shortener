import React from 'react';
import styled from 'styled-components';

const Form = styled.form`
    display: block;
    margin-bottom: 20px;
`;

export default (props) => {
    return (
        <Form {...props} >
            <div className="form-group">
                <label htmlFor="url">URL:</label>
                <input type="text" className="form-control" id="url" name="url" placeholder="Enter URL..."/>
            </div>
            <div className="form-group">
                <label htmlFor="expires_at">Expires:</label>
                <input
                    type="date"
                    className="form-control"
                    id="expires_at"
                    name="expires_at"
                    placeholder="Valid until..."
                    defaultValue={null}
                />
            </div>
            <button type="submit" className="btn btn-primary">Submit</button>
        </Form>
    );
}
