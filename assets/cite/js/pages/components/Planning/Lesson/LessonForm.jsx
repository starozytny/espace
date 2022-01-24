import React, { Component } from 'react';

import axios                   from "axios";
import toastr                  from "toastr";
import Routing                 from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Alert }               from "@dashboardComponents/Tools/Alert";
import { LoaderElement }       from "@dashboardComponents/Layout/Loader";

import Formulaire              from "@dashboardComponents/functions/Formulaire";
import TimeHelper              from "@citeComponents/functions/timeHelper";
import Sanitize                from "@commonComponents/functions/sanitaze";
import Helper                  from "@commonComponents/functions/helper";

import { ClassesList } from "../Classe/ClassesList";

function getNewEnd(start, eleves, end, slot) {
    let firstLoop = true;
    eleves.forEach(item => {
        let process = true;
        let mode = item.classe.mode;
        let duration = item.classe.durationString;
        if(mode !== 1 && firstLoop === false){
            process = false;
        }

        if(process){

            if(duration == null){
                duration = slot.durationString;
            }

            let st = TimeHelper.getMillisByPattern(start);
            let add = TimeHelper.getMillisByPattern(duration);

            let nb = 1;

            let newEnd = new Date();
            newEnd.setTime(st + (add * nb));

            start = Sanitize.toFormatTime(newEnd);
        }
        firstLoop = false;
    })

    if(TimeHelper.getMillisByPattern(start) > TimeHelper.getMillisByPattern(end)) {
        return false;
    }

    return start;
}

export class LessonForm extends Component {
    constructor(props) {
        super(props);

        this.state = {
            sticky: false,
            warning: null,
            success: false,
            error: null,
            classes: [],
            start: null,
            end: null,
            eleves: [],
            vacants15: 0,
            vacants20: 0,
            vacants22: 0,
            vacants30: 0
        }

        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleSelectType = this.handleSelectType.bind(this);
        this.handleSelectClasse = this.handleSelectClasse.bind(this);
        this.handleSelectEleve = this.handleSelectEleve.bind(this);
        this.handleSelectPre = this.handleSelectPre.bind(this);
    }

    componentDidMount = () => {
        Helper.toTop();
    }

    componentWillUnmount = () => { window.removeEventListener("scroll", window.fn) }

    handleSelectType = () => {
        this.setState({
            classes: [],
            end: null,
            eleves: [],
            warning: null,
            success: false,
            error: null
        })
    }

    handleSelectPre = (cours) => {

    }

    handleSelectClasse = (cl, canClick) => {
        const { slot } = this.props;
        const { classes, eleves, start } = this.state;

        if(canClick){
            let newClasses = [];

            let find = false;
            classes.forEach(el => {
                if(el.id === cl.id){
                    find = true;
                }
            })

            if(find){ // = close select classe
                let newEleves = eleves.filter(el => el.classe.id !== cl.id); //remove all eleve from this classe
                newClasses = classes.filter(el => el.id !== cl.id);

                let newEnd = getNewEnd(start, newEleves, slot.endString, slot);

                this.setState({ classes: newClasses, eleves: newEleves, end: newEnd })
            }else{ // = add
                newClasses.push(cl);

                this.setState((prevState) => {
                    if(prevState.classes.length < 4){
                        return { classes: [...prevState.classes, ...newClasses] }
                    }else{
                        return { classes: prevState.classes, warning: 'Vous ne pouvez pas mélanger plus de 4 classes.' }
                    }
                })
            }
        }else{
            toastr.error('Vous ne pouvez pas déselectionner cette classe car au moins un élève a déjà accepté votre proposition.')
        }
    }

    handleSelectEleve = (eleveClasse, eleve, typeVacant=null, isAdd =null) => {

    }

    handleSubmit = (e) => {
        e.preventDefault();

    }

    render () {
        const { role, slot, elevesVacant, classesVacant } = this.props;
        const { warning, error, success, sticky, classes, start, end, eleves, } = this.state;

        // ---------------------------------------------------------
        // ----------------- init values
        // ---------------------------------------------------------
        let activityName = slot.activity.name;
        let cycleName = slot.cycle && slot.cycle.name;
        let classroomName = slot.classroom ? slot.classroom.num + " " + slot.classroom.name.toLowerCase() : "Salle indéfinie";
        let centreName = slot.center ? slot.center.name : "Centre indéfinie";
        let levelName = slot.level && "- " + slot.level.name;

        // get only classes match with slot
        let classesAvailable = [];
        this.props.classes.forEach(elem => {
            if(elem.activity.id === slot.activity.id){
                if(slot.cycle){
                    let cycleClasse = elem.cycle && elem.cycle.id;
                    let cycleSlot = slot.cycle && slot.cycle.id;

                    if(cycleClasse === cycleSlot){

                        if(slot.level){
                            let levelClasse = elem.level && elem.level.id;
                            let levelSlot = slot.level && slot.level.id;

                            if(levelClasse === levelSlot){
                                classesAvailable.push(elem);
                            }
                        }else{
                            classesAvailable.push(elem);
                        }
                    }
                }else{
                    classesAvailable.push(elem);
                }
            }
        })

        return <>
            <p className="form-infos">
                <span>{centreName} {[classroomName]}</span> <span className="activity"> - {activityName} {cycleName} {levelName}</span>
                <br/><br/>
                <span>
                    Créneau horaire du {slot.dayString.toLowerCase()} de {TimeHelper.betterDisplayTimeNotDot(slot.startString)} à {TimeHelper.betterDisplayTimeNotDot(slot.endString)}.
                </span>
            </p>
            <form onSubmit={this.handleSubmit}>

                <div className="formulaire" id="trigger-1">
                    {success !== false && <Alert type="info">{success}</Alert>}

                    {classesAvailable.length === 0 ? <Alert>Aucune classe disponible.</Alert> : <ClassesList classes={classesAvailable}
                                                                                                             selectedClasses={classes} slot={slot}
                                                                                                             onSelectClass={this.handleSelectClasse}
                    />}
                </div>

                <div className={"recap" + (sticky ? " sticky" : "")}>
                    <div className="recap-content">
                        <span>Récapitulatif</span>
                        <div>
                            <div className="recap-classe">
                                {/*<p className="name">Cours de {classe.length !== 0 && classe.map((el, index) => {*/}
                                {/*    return <React.Fragment key={el.id}>{index !== 0 && ", "} {el.nameCycleLevel}</React.Fragment>*/}
                                {/*})}</p>*/}
                                {/*<p className="time">De {start} à {end ? end : "?"} le {slot.dayString.toLowerCase()}</p>*/}
                                {/*<p className="nb">*/}
                                {/*    <span className="icon-user" />*/}
                                {/*    {eleves.length} / {classe.length !== 0 ? classe[classe.length - 1].max : 0} élèves conseillés*/}
                                {/*</p>*/}
                            </div>
                            <div className="recap-eleves">

                            </div>
                        </div>
                        <div>
                            {warning && <Alert type="warning">{warning}</Alert>}
                            {error && error.map((err, index) => {
                                return <Alert key={index} type="danger">{err}</Alert>
                            })}
                        </div>
                    </div>
                    <div className="form-button">
                        <button type="submit" className="btn btn-primary">Valider la saisie</button>
                    </div>
                </div>
            </form>
        </>
    }
}