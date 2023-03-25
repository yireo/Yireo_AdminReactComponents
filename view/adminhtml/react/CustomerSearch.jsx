import React from "react";
import AbstractSearch from "./AbstractSearch";

const CustomerSearch = (props) => {
    return (
        <AbstractSearch
            {...props}
            panelTitle="Search for customers"
            emptyLabel="No customer data found"
            fields={{id: 'ID', name: 'Name', email: 'Email', group_label: 'Group', website_label: 'Website'}}
            labelAjaxUrl={window.yireo_react.customerLabelAjaxUrl}
            searchAjaxUrl={window.yireo_react.customerSearchAjaxUrl}
        />
    );
};

export default CustomerSearch;
