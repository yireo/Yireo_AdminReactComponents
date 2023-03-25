import React from "react";
import AbstractSearch from "./AbstractSearch";

const ProductSearch = (props) => {
    return (
        <AbstractSearch
            {...props}
            panelTitle={props.panelTitle || "Search for products"}
            emptyLabel={props.emptyLabel || "No product data found"}
            fields={props.fields || {id: 'ID', name: 'Name', sku: 'SKU'}}
            labelAjaxUrl={window.yireo_react.productLabelAjaxUrl}
            searchAjaxUrl={window.yireo_react.productSearchAjaxUrl}
        />
    );
};

export default ProductSearch;
