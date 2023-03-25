import React from "react";
import AbstractSearch from "./AbstractSearch";

const OrderSearch = (props) => {
    return (
        <AbstractSearch
            {...props}
            panelTitle={props.panelTitle || "Search for orders"}
            emptyLabel={props.emptyLabel || "No order data found"}
            fields={props.fields || {
                id: 'ID',
                increment_id: 'Increment ID',
                customer_email: 'Customer Email',
                created_at: 'Created At'
            }}
            labelAjaxUrl={window.yireo_react.orderLabelAjaxUrl}
            searchAjaxUrl={window.yireo_react.orderSearchAjaxUrl}
        />
    );
};

export default OrderSearch;
