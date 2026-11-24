import React, {useState, useEffect} from "react";
import SearchPanel from "./AbstractSearch/SearchPanel";
import ModalOverlay from "./AbstractSearch/ModalOverlay";

const AbstractSearch = (props) => {
    const koDataProvider = props.dataProvider;
    const koViewModel = props.viewModel;
    const fieldName = props.fieldName;
    const [itemId, setItemId] = useState(props.id);
    const [itemLabel, setItemLabel] = useState(props.emptyLabel);
    const [showSearchPanel, setShowSearchPanel] = useState(false);

    const setLabelViaAjax = (id) => {
        if (!(parseInt(id, 10) > 0)) return false;

        const searchUrl = props.labelAjaxUrl + "?id=" + encodeURIComponent(id);
        fetch(searchUrl)
            .then((response) => {
                if (!response.ok) {
                    throw Error(response.statusText);
                }
                return response;
            })
            .then((response) => response.json())
            .then((responseJson) => setItemLabel(responseJson.label))
            .catch(error => console.log(error));

        return true;
    };

    const changeItemData = (value) => {
        const id = ('' + value).replace(/\D/g, '');
        setLabelViaAjax(id);
        setItemId(id);

        if (koDataProvider) {
            requirejs('uiRegistry').get(koDataProvider).data[fieldName] = id;
        }

        if (koViewModel) {
            requirejs('uiRegistry').get(koViewModel).value(id);
        }
    };

    useEffect(() => {
        setLabelViaAjax(props.id);
    }, [props.id]);

    return (
        <div>
            <div className="id-preview-container">
                <div className="admin__control-addon id-preview">
                    <input className="admin__control-text" type="text" name={fieldName} placeholder="Numeric ID" inputMode="numeric"
                           onChange={(event) => changeItemData(event.target.value)}
                           value={itemId} maxLength="11"/>
                    <label className="admin__addon-suffix">
                        <span>{itemLabel}</span>
                    </label>
                </div>

                <div className="button">
                    <small>
                        <button onClick={() => setShowSearchPanel(true)}>Search</button>
                    </small>
                </div>
            </div>

            {showSearchPanel && <div>
                <ModalOverlay
                    panelTitle={props.panelTitle}
                    onClose={() => setShowSearchPanel(false)}
                >
                    <SearchPanel
                        setItemId={changeItemData}
                        ajaxUrl={props.searchAjaxUrl}
                        fields={props.fields}
                        onClose={() => setShowSearchPanel(false)}
                    />
                </ModalOverlay>
            </div>}
        </div>
    )
};

export default AbstractSearch;
