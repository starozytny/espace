import React, { Component } from 'react';

import axios             from "axios";
import toastr            from "toastr";
import Routing           from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Page }          from "@dashboardComponents/Layout/Page";
import { Days }          from "@dashboardComponents/Tools/Days";
import { LoaderElement } from "@dashboardComponents/Layout/Loader";

import Data from "@citeComponents/functions/data";
import Sort from "@commonComponents/functions/sort";

import { SelectTeacher } from "./SelectTeacher";
import { Slots }         from "./Slots";
import { LessonCreate }  from "./Lesson/LessonCreate";
import { LessonUpdate }  from "./Lesson/LessonUpdate";

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
            classes: [],
            elevesVacant: [],
            classesVacant: [],
            slots: [],
            teacher: null,
            slot: null,
            lesson: null
        }

        this.handleChangePlanning = this.handleChangePlanning.bind(this);
        this.handleSelectDay = this.handleSelectDay.bind(this);
        this.handleChangeContext = this.handleChangeContext.bind(this);

        this.handleUpdateSlot = this.handleUpdateSlot.bind(this);
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
        const { classes, teacher } = this.state;

        this.setState({ context, slot, lesson });
        if(classes.length === 0){
            Data.getClassesByTeacher(this, teacher, "planning");
        }
    }

    handleUpdateSlot = (slot, lesson, nContext="") => {
        const { data } = this.state;

        let nData;
        if(nContext !== "lessons"){

            let dataSlot = data.filter(el => el.id === slot.id)[0];
            let lessons = [];

            if(nContext === "delete"){
                Object.entries(dataSlot.lessons).forEach(([key, el]) => {
                    if(el.id !== lesson.id){
                        lessons.push(el);
                    }
                })
            }else{
                let find = false;
                Object.entries(dataSlot.lessons).forEach(([key, el]) => {
                    if(el.id === lesson.id){
                        el = lesson;
                        find=true;
                    }
                    lessons.push(el);
                })
                if(!find){
                    lessons.push(lesson);
                }
            }

            dataSlot.lessons = lessons;

            nData = data.filter(el => el.id !== slot.id);
            nData.push(dataSlot)
        }else{
            nData=slot;
        }

        nData.sort(Sort.compareStart);

        this.setState({ data: nData })
        this.handleChangeContext("list");
    }

    render () {
        const { role, developer } = this.props;
        const { loadPageError, loadData, context, teachers, dayActive, slots, classes, elevesVacant, classesVacant, slot, lesson } = this.state;

        let content;
        switch (context){
            case "create":
                content = <LessonCreate slot={slot} classes={classes} role={role}
                                        elevesVacant={elevesVacant} classesVacant={classesVacant}
                                        onUpdateSlot={this.handleUpdateSlot}
                                        onChangeContext={this.handleChangeContext}/>
                break;
            case "update":
                content = <LessonUpdate slot={slot} classes={classes} lesson={lesson} role={role}
                                        elevesVacant={elevesVacant} classesVacant={classesVacant}
                                        onUpdateSlot={this.handleUpdateSlot}
                                        onChangeContext={this.handleChangeContext}/>
                break;
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