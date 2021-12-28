import React, { Component } from "react";

import Routing           from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { SelectReactSelectize } from "@dashboardComponents/Tools/Fields";
import { Button } from "@dashboardComponents/Tools/Button";
import Formulaire from "@dashboardComponents/functions/Formulaire";

export class SelectTeacher extends Component {
    constructor(props) {
        super(props);

        this.state = {
            teacher: "",
            teacherName: "",
            errors: []
        }

        this.handleChangeSelect = this.handleChangeSelect.bind(this);
    }

    handleChangeSelect = (e) => {
        const { teacher } = this.state;

        let value = "";
        if(e !== undefined){
            value = e.value
            if(value !== teacher){
                Formulaire.loader(true);
                // this.props.onChangePlanning(value);
                // this.props.onSaveNameTeacher(e.label, value);
            }
        }

        this.setState({ teacher: value, teacherName: "" })
    }

    render () {
        const { role, developer, teachers } = this.props;
        const { teacher, errors } = this.state;

        let teacherName = "", elem = "";
        teachers.forEach(el => {
            if(el.value === teacher){
                teacherName = el.label;
                elem = el;
            }
        })

        let quotas;
        if(elem !== ""){
            quotas = <div className="quotas">
                <div className="line line-2">
                    {/*<div className="form-group quota-webservice">*/}
                    {/*    <div>*/}
                    {/*        <div>Total des créneaux horaires alloués : <b>{elem.quotaSlot}</b></div>*/}
                    {/*        <div>Temps utilisé : <b>{elem.lessonsDuration ? elem.lessonsDuration : "0h"}</b></div>*/}
                    {/*    </div>*/}
                    {/*</div>*/}
                    <div className="form-group quota-cursus">
                        <div>
                            Temps accordé aux instruments : <b>{elem.quotaInstru ? elem.quotaInstru : "/"}</b> et aux Fms : <b>{elem.quotaFm ? elem.quotaFm : "/"}</b>
                        </div>
                    </div>
                </div>

            </div>
        }

        return <div className="toolbar">
            <div className="item select">
                <div className="line">
                    <SelectReactSelectize items={teachers} placeholder="Sélectionner un professeur" identifiant="teacher"
                                          valeur={teacher} errors={errors} onChange={this.handleChangeSelect}/>
                </div>
                {role !== "teacher" && teacherName && <div className="selected-teacher-name">
                    <span>{teacherName}</span>
                    {parseInt(developer) === 1 && <Button element="a">Modifier le planning</Button>}
                </div>}
                {quotas}
            </div>
        </div>
    }
}