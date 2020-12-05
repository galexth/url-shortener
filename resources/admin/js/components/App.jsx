import React, { useState, useCallback, useMemo } from 'react';
import ReactDOM from 'react-dom';
import {NotificationManager, NotificationContainer} from "react-notifications";
import Moment from "react-moment";
import {truncate} from 'lodash';
import Button from 'react-bootstrap/Button';

import Table from './Table';
import api from '../api';

import 'regenerator-runtime/runtime'

import 'react-notifications/lib/notifications.css';
import 'bootstrap/dist/css/bootstrap.min.css';

function Spinner() {
    return (
        <span className="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
    );
}

function App() {

    const columns = useMemo(
        () => [
            {
                Header: 'ID',
                accessor: 'id',
                style: {
                    width: '60px',
                },
                disableFilters: true,
            },
            {
                Header: 'Short code',
                accessor: 'short_code',
                disableSortBy: true,
                style: {
                    width: '120px',
                },
            },
            {
                Header: 'Hits',
                accessor: 'hits',
                style: {
                    width: '60px',
                },
                disableFilters: true,
            },
            {
                Header: 'URL',
                accessor: 'url',
                disableSortBy: true,
                Cell: ({value}) => {
                    return truncate(value, {length: 65});
                },
            },
            {
                Header: 'Expires at',
                accessor: 'expires_at',
                style: {
                    width: '185px',
                },
                Cell: ({value}) => {
                    return (value && <Moment format="DD-MM-YYYY HH:mm:SS">{value}</Moment>);
                },
                disableFilters: true,
            },
            {
                Header: 'Actions',
                accessor: 'delete',
                disableSortBy: true,
                Cell: (tableProps) => {
                    const [isLoading, setIsLoading] = useState(false);
                    return (
                        <Button
                            variant="outline-danger"
                            disabled={isLoading}
                            onClick={() => {
                                setIsLoading(true);
                                const id = tableProps.row.values.id;
                                api.destroy(id).then(({data}) => {
                                    if (data.deleted) {
                                        setData((prevState => [...prevState].filter((el) => el.id !== id)));
                                        NotificationManager.success(`ID ${id} deleted`);
                                        return true;
                                    }
                                    NotificationManager.error(`Failed to delete ID ${id}`);
                                }).catch(err => NotificationManager.error('Something wrong')
                                ).finally(() => setIsLoading(false));
                            }}
                        >
                            {isLoading ? <Spinner/> : 'Delete'}
                        </Button>
                    )
                },
                disableFilters: true,
            },
        ],
        []
    )

    const [data, setData] = useState([])
    const [loading, setLoading] = useState(false)
    const [total, setTotal] = useState(0)
    const [pageCount, setPageCount] = useState(0)

    const fetchData = useCallback(({ pageSize, pageIndex, sortBy, filters }) => {
        setLoading(true)

        api.index({
            page: pageIndex + 1,
            pageSize,
            sortBy,
            filters: filters.reduce((map, filter) => {
                map[filter.id] = filter.value;
                return map;
            }, {})
        }).then(({data}) => {
            setData(data.data);
            setPageCount(data.last_page);
            setTotal(data.total);
        }).catch(err => NotificationManager.error('Something wrong'))
            .finally(() => setLoading(false));
    }, [])

    return (
        <>
        <Table
            columns={columns}
            data={data}
            fetchData={fetchData}
            loading={loading}
            pageCount={pageCount}
            total={total}
        />
        <NotificationContainer/>
        </>
    )
}

export default App;
