import React, { Component } from 'react';

import axios             from "axios";
import toastr            from "toastr";
import Routing           from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Page }          from "@dashboardComponents/Layout/Page";
import { Days }          from "@dashboardComponents/Tools/Days";
import { LoaderElement } from "@dashboardComponents/Layout/Loader";

import Data from "@citeComponents/functions/data";

import { SelectTeacher } from "./SelectTeacher";
import { Slots }         from "./Slots";

function handleGetPlanning(){

}

export class Planning extends Component {
    constructor(props) {
        super(props);

        this.state = {
            isOnInstru: props.isOpenInstru === "1",
            isOnFm: props.isOpenFm === "1",
            context: "list",
            loadPageError: false,
            loadData: true,
            dayActive: 0,
            teachers: [],
            slots: []
        }

        this.handleChangePlanning = this.handleChangePlanning.bind(this);
        this.handleSelectDay = this.handleSelectDay.bind(this);
    }

    componentDidMount = () => {
        if(this.props.role === "admin"){
            Data.getTeachers(this, "select");
        }else{
            Data.getPlanning(this, this.props.teacherId ? parseInt(this.props.teacherId) : "");
        }
        this.setState({ loadData: false });
    }

    handleChangePlanning = (teacher) => { Data.getPlanning(this, teacher); }

    handleSelectDay = (dayActive, atLeastOne) => { if(atLeastOne) { this.setState({ dayActive }) } }

    handleChangeContext = (context, slot=null, lesson=null) => {
        this.setState({ context, slot, lesson });
    }

    render () {
        const { role, developer } = this.props;
        const { loadPageError, loadData, context, teachers, dayActive, slots } = this.state;

        let content;
        switch (context){
            default:
                content = loadData ? <LoaderElement /> : <>
                    {role !== "teacher" && <SelectTeacher role={role} developer={developer} teachers={teachers} onChangePlanning={this.handleChangePlanning}/>}
                    <Days dayActive={dayActive} data={slots} onSelectDay={this.handleSelectDay} />
                    <Slots {...this.state} developer={developer} onChangeContext={this.handleChangeContext} />
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