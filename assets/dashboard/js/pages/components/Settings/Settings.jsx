import React, { Component } from 'react';

import Routing           from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Page }          from "@dashboardComponents/Layout/Page";
import { LoaderElement } from "@dashboardComponents/Layout/Loader";
import Formulaire        from "@dashboardComponents/functions/Formulaire";

import { SettingsForm }  from "./SettingsForm";

export class Settings extends Component {
    constructor(props) {
        super();

        this.state = {
            context: "list",
            loadPageError: false,
            loadData: true,
            data: null
        }
    }

    componentDidMount = () => {
        Formulaire.axiosGetData(this, Routing.generate('api_settings_index'))
    }

    render () {
        const { loadPageError, context, loadData, data } = this.state;

        let content = null, havePagination = false;
        switch (context){
            default:
                content = loadData ? <LoaderElement /> : <SettingsForm who={this.props.who} data={data}/>
                break;
        }

        return <>
            <Page ref={this.page} haveLoadPageError={loadPageError} havePagination={havePagination}>
                {content}
            </Page>
        </>
    }
}