import React, { Component } from 'react';

import { Page }           from "@dashboardComponents/Layout/Page";
import { Aside }          from "@dashboardComponents/Tools/Aside";
import { Alert }          from "@dashboardComponents/Tools/Alert";
import { SwitcherButton } from "@dashboardComponents/Tools/Button";
import { Select, SelectReactSelectize } from "@dashboardComponents/Tools/Fields";

import Manage from "@citeComponents/functions/manage";
import Data from "@citeComponents/functions/data";
import {LoaderElement} from "@dashboardComponents/Layout/Loader";

export class Levels extends Component {
    constructor(props) {
        super(props);

        this.state = {
            isOpen: props.isOpenLevel === "true",
            loadPageError: false,
            loadData: true,
            data: [],
            teacher: "",
            center: "",
            teachers: [],
            centers: [],
            errors: [],
        }

        this.page = React.createRef();

        this.handleUpdateData = this.handleUpdateData.bind(this);

        this.handleChangeSelect = this.handleChangeSelect.bind(this);
        this.handleSwitchManage = this.handleSwitchManage.bind(this);
    }

    componentDidMount = () => {
        if(this.props.role === "admin"){ Data.getTeachers(this, "select");}
    }

    handleUpdateData = (data) => { this.setState({ currentData: data })  }

    handleChangeSelect = (name, e) => {
        this.setState({ [name]: e !== undefined ? e.value : "" });
    }

    handleSwitchManage = () => { Manage.switchLevel(this, this.state.isOpen); }

    render () {
        const { role, developer } = this.props; // developer = string number / 1 = developer
        const { isOpen, loadData, loadPageError, errors, data, teacher, center, teachers, centers } = this.state;

        let toolbar = <div className="toolbar">
            {role === "admin" && <div className="item select">
                <div className="line">
                    <SelectReactSelectize items={teachers} placeholder="Sélectionner un professeur" identifiant="teacher"
                                          valeur={teacher} errors={errors} onChange={(e) => this.handleChangeSelect("teacher", e)}/>
                </div>
            </div>}
            <div className="item select">
                <div className="line">
                    <Select items={centers} placeholder="Sélectionner un centre" identifiant="center"
                            valeur={center} errors={errors} onChange={this.handleChangeSelect}/>
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

            {teacher === "" ? <Alert>Veuillez sélectionner un professeur.</Alert> : <div>Hello</div>}
        </div>

        return <>
            <Page ref={this.page} haveLoadPageError={loadPageError}
                  havePagination={false} taille={data && data.length} data={data} onUpdate={this.handleUpdateData}
            >
                {loadData ? <LoaderElement /> : content}
                {/*<Aside ref={this.aside} content={contentAside} />*/}
            </Page>
        </>
    }
}