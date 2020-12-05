import React, {useEffect, useMemo} from "react";
import {useAsyncDebounce, useFilters, usePagination, useSortBy, useTable} from "react-table";
import ReactPaginate from "react-paginate";
import Form from "react-bootstrap/Form";
import BTable from "react-bootstrap/Table";

function DefaultColumnFilter({column: { filterValue, setFilter }}) {
    return (
        <Form.Control value={filterValue || ''} onChange={e => setFilter(e.target.value || undefined)}/>
    )
}

function Sorting({isSortedDesc}) {
    return isSortedDesc
        ? (
            <svg width="1em" height="1em" viewBox="0 0 16 16" className="bi bi-sort-down"
               fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fillRule="evenodd"
                  d="M3 2a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-1 0v-10A.5.5 0 0 1 3 2z"/>
            <path fillRule="evenodd"
                  d="M5.354 10.146a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L3 11.793l1.646-1.647a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 9a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z"/>
            </svg>
        )
        : (
            <svg width="1em" height="1em" viewBox="0 0 16 16" className="bi bi-sort-up-alt" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fillRule="evenodd" d="M3 14a.5.5 0 0 0 .5-.5v-10a.5.5 0 0 0-1 0v10a.5.5 0 0 0 .5.5z"/>
                <path fillRule="evenodd" d="M5.354 5.854a.5.5 0 0 0 0-.708l-2-2a.5.5 0 0 0-.708 0l-2 2a.5.5 0 1 0 .708.708L3 4.207l1.646 1.647a.5.5 0 0 0 .708 0zM7 6.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5zm0 3a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 0-1h-5a.5.5 0 0 0-.5.5zm0 3a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7a.5.5 0 0 0-.5.5zm0-9a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 0-1h-1a.5.5 0 0 0-.5.5z"/>
            </svg>
        );
}

function Table({columns, data, fetchData, loading, pageCount: controlledPageCount, total,}) {
    const defaultColumn = useMemo(
        () => ({
            Filter: DefaultColumnFilter,
        }),
        []
    )

    const defaultPropGetter = () => ({})

    const {
        getTableProps,
        getTableBodyProps,
        getHeaderProps = defaultPropGetter,
        getColumnProps = defaultPropGetter,
        headerGroups,
        prepareRow,
        page,
        pageCount,
        gotoPage,
        setPageSize,
        state: { pageIndex, pageSize, sortBy, filters },
    } = useTable(
        {
            columns,
            data,
            defaultColumn,
            initialState: { pageIndex: 0 },
            manualPagination: true,
            manualFilters: true,
            manualSortBy: true,
            pageCount: controlledPageCount,
        },
        useFilters,
        useSortBy,
        usePagination
    )

    const onFetchDataDebounced = useAsyncDebounce(fetchData, 500);

    useEffect(() => {
        onFetchDataDebounced({ pageIndex, pageSize, sortBy, filters })
    }, [onFetchDataDebounced, pageIndex, pageSize, sortBy, filters])

    return (
        <div className="container">
            <br/>
            <br/>
            <div className="row justify-content-center">
                <div className="col-md-6">
                    <ReactPaginate
                        previousLabel={'<'}
                        nextLabel={'>'}
                        breakLabel={'...'}
                        pageClassName={'page-item'}
                        pageLinkClassName={'page-link'}
                        previousClassName={'page-item'}
                        previousLinkClassName={'page-link'}
                        nextClassName={'page-item'}
                        nextLinkClassName={'page-link'}
                        breakClassName={'page-item'}
                        breakLinkClassName={'page-link'}
                        pageCount={pageCount}
                        marginPagesDisplayed={2}
                        pageRangeDisplayed={5}
                        onPageChange={(data) => gotoPage(data.selected)}
                        containerClassName={'pagination'}
                        subContainerClassName={'pages pagination'}
                        activeClassName={'active'}
                    />
                </div>
                <div className="col-md-4">
                    {loading ? (
                        <div className="spinner-border" role="status">
                            <span className="sr-only">Loading...</span>
                        </div>
                    ) : (
                        <div>Showing <strong>{pageSize}</strong> of <strong>{total}</strong></div>
                    )}
                </div>
                <div className="col-md-2">
                    <Form.Control
                        as="select"
                        size="md"
                        value={pageSize}
                        onChange={e => setPageSize(Number(e.target.value))}
                    >
                        {[10, 20].map(pageSize => (
                            <option key={pageSize} value={pageSize}>Show {pageSize}</option>
                        ))}
                    </Form.Control>
                </div>
            </div>
            <div className="row justify-content-center">
                <div className="col-md-12">
                    <BTable striped bordered hover size="sm" {...getTableProps()}>
                        <thead>
                        {headerGroups.map(headerGroup => (
                            <tr {...headerGroup.getHeaderGroupProps()}>
                                {headerGroup.headers.map(column => (
                                    <th {...column.getHeaderProps([
                                        {className: 'th-' + column.id,},
                                        getColumnProps(column),
                                        getHeaderProps(column),
                                        column.getSortByToggleProps()
                                    ])}>
                                        {column.render('Header')}
                                        {column.canFilter ? column.render('Filter') : null}
                                        <span>
                                            {column.isSorted ? <Sorting isSortedDesc={column.isSortedDesc}/> : ''}
                                        </span>
                                    </th>
                                ))}
                            </tr>
                        ))}
                        </thead>
                        <tbody {...getTableBodyProps()}>
                        {page.map(row => {
                            prepareRow(row);
                            return (
                                <tr {...row.getRowProps()}>
                                    {row.cells.map(cell => {
                                        return (
                                            <td {...cell.getCellProps()}>
                                                {cell.render('Cell')}
                                            </td>
                                        )
                                    })}
                                </tr>
                            )
                        })}
                        </tbody>
                    </BTable>
                </div>
            </div>
        </div>
    )
}

export default Table;
