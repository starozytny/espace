import React, { Component } from 'react';

import axios             from "axios";
import toastr            from "toastr";
import Routing           from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Page }          from "@dashboardComponents/Layout/Page";
import { LoaderElement } from "@dashboardComponents/Layout/Loader";

import Data from "@citeComponents/functions/data";

import { SelectTeacher } from "./SelectTeacher";

function handleGetPlanning(){

}

export class Planning extends Component {
    constructor(props) {
        super(props);

        this.state = {
            context: "list",
            loadPageError: false,
            loadData: true,
            teacher: props.teacherId ? parseInt(this.props.teacherId) : "",
            teachers: []
        }

    }

    componentDidMount = () => {
        if(this.props.role === "admin"){
            Data.getTeachers(this, "select");
        }else{

        }
        this.setState({ loadData: false });
    }

    render () {
        const { role, developer } = this.props;
        const { loadPageError, loadData, context, teachers, teacher } = this.state;

        let content;
        switch (context){
            default:
                content = loadData ? <LoaderElement /> : <>
                    {role !== "teacher" && <SelectTeacher role={role} developer={developer} teachers={teachers} />}
                    {/*<Days {...this.state} onSelectDay={this.handleSelectDay} />*/}
                    {/*<Slot {...this.state} developer={developer} onChangeContext={this.handleChangeContext} onDelete={this.handleDelete} onReOrder={this.handleReOrder}/>*/}
                </>
                break;
        }

        return <>
            <Page haveLoadPageError={loadPageError}>
                {content}
            </Page>
        </>
    }
}