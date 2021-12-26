import React, { Component } from 'react';

import { Page }           from "@dashboardComponents/Layout/Page";
import { Alert }          from "@dashboardComponents/Tools/Alert";
import { SwitcherButton } from "@dashboardComponents/Tools/Button";
import { LoaderElement }  from "@dashboardComponents/Layout/Loader";
import { Select, SelectReactSelectize } from "@dashboardComponents/Tools/Fields";

import Manage from "@citeComponents/functions/manage";
import Data from "@citeComponents/functions/data";
import {LevelsList} from "./LevelsList";

export class Levels extends Component {
    constructor(props) {
        super(props);

        this.state = {
            isOpen: props.isOpenLevel === "1",
            loadPageError: false,
            loadData: true,
            data: [],
            teacher: props.teacherId ? parseInt(this.props.teacherId) : "",
            center: 9,
            teachers: [],
            centers: [],
            classes: [],
            errors: [],
        }

        this.page = React.createRef();

        this.handleUpdateData = this.handleUpdateData.bind(this);

        this.handleChangeSelect = this.handleChangeSelect.bind(this);
        this.handleSwitchManage = this.handleSwitchManage.bind(this);
    }

    componentDidMount = () => {
        if(this.props.role === "admin"){
            Data.getTeachers(this, "select");
        }else{
            Data.getClassesByTeacher(this, this.state.teacher, "centers")
        }
    }

    handleUpdateData = (data) => { this.setState({ currentData: data })  }

    handleChangeSelect = (name, e) => {
        let value = e !== undefined ? e.value : "";

        if(name === "teacher"){
            Data.getClassesByTeacher(this, value, "centers")
        }

        this.setState({ [name]: value });
    }

    handleSwitchManage = () => { Manage.switchLevel(this, this.state.isOpen); }

    render () {
        const { role, developer } = this.props; // developer = string number | 1 = developer
        const { isOpen, loadData, loadPageError, errors, data, teacher, center, teachers, centers, classes } = this.state;

        let toolbar = <div className="toolbar">
            {role === "admin" && <div className="item select">
                <div className="line">
                    <SelectReactSelectize items={teachers} placeholder="Sélectionner un professeur" identifiant="teacher"
                                          valeur={teacher} errors={errors} onChange={(e) => this.handleChangeSelect("teacher", e)}/>
                </div>
            </div>}
            <div className="item select">
                <div className="line">
                    <SelectReactSelectize items={centers} placeholder="Sélectionner un centre" identifiant="center"
                            valeur={center} errors={errors} onChange={(e) => this.handleChangeSelect("center", e)}/>
                </div>
            </div>
        </div>

        let content = (!isOpen && role !== "admin") ? <Alert>La gestion des niveaux n'est pas ouverte.</Alert> : <div>
            {role === "admin" ? <div className="panel-and-toolbar">
                <div className="panel-btns">
                    <SwitcherButton status={isOpen} onClick={this.handleSwitchManage}>Gestion des niveaux</SwitcherButton>
                </div>
                {toolbar}
            </div> : <>{toolbar}</>}

            {teacher === "" ? <Alert>Veuillez sélectionner un professeur.</Alert> : (
                center === "" ? <Alert>Veuillez sélectionner un centre</Alert> : <LevelsList {...this.state} />
            )}
        </div>

        return <>
            <Page ref={this.page} haveLoadPageError={loadPageError}
                  havePagination={false} taille={data && data.length} data={data} onUpdate={this.handleUpdateData}
            >
                {loadData ? <LoaderElement /> : content}
            </Page>
        </>
    }
}