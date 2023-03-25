import React from "react";
import AbstractSearch from "./AbstractSearch";

const CustomerSearch = (props) => {
    return (
        <AbstractSearch
            {...props}
            panelTitle={props.panelTitle || "Search for customers"}
            emptyLabel={props.emptyLabel || "No customer data found"}
            fields={props.fields || {id: 'ID', name: 'Name', email: 'Email', group_label: 'Group', website_label: 'Website'}}
            labelAjaxUrl={window.yireo_react.customerLabelAjaxUrl}
            searchAjaxUrl={window.yireo_react.customerSearchAjaxUrl}
        />
    );
};

export default CustomerSearch;
