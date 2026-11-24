import React from "react";
import ReactDOM from "react-dom";
import CustomerSearch from './CustomerSearch';
import ProductSearch from './ProductSearch';
import OrderSearch from './OrderSearch';

const componentMapping = {
    CustomerSearch,
    ProductSearch,
    OrderSearch
};

window.renderReactComponent = (componentId, elementId, props = {}) => {
    if (!elementId || !componentId) {
        throw new Error('renderReactComponent requires 2 arguments');
    }

    if (!Object.prototype.hasOwnProperty.call(componentMapping, componentId)) {
        throw new Error('Unknown React component: ' + componentId);
    }

    const element = document.getElementById(elementId);
    if (!element) {
        throw new Error('Element not found: ' + elementId);
    }

    const Component = componentMapping[componentId];
    ReactDOM.render(<Component {...props} />, element);
};
