import React, { Component } from "react";

import axios             from "axios";
import Routing           from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Alert }              from "@dashboardComponents/Tools/Alert";
import { LoaderElement }      from "@dashboardComponents/Layout/Loader";
import { Button, ButtonIcon } from "@dashboardComponents/Tools/Button";

import Formulaire         from "@dashboardComponents/functions/Formulaire";

function process (self, url, eleve, data) {
    self.setState({ loadElemId: eleve.id })
    axios.post(url, data)
        .then(function (response) {
            let resp = response.data;
            self.handleUpdateClasse(JSON.parse(resp.classe), JSON.parse(resp.groupes))
            self.setState({ loadElem: 0 })
        })
        .catch(function (error) {
            Formulaire.displayErrors(self, error);
        })
        .then(function () {})
    ;
}

function getUrl(urlName, groupId, eleve){
    return Routing.generate(urlName, {'group': groupId, 'eleve': eleve.id})
}

export class LevelsItem extends Component {
    constructor(props) {
        super(props);

        this.state = {
            loadElemId: 0
        }

        this.handleReset = this.handleReset.bind(this);
    }

    handleReset = (groupId, eleve) => {
        process(this, getUrl('api_level_reset', groupId, eleve), eleve, {})
        this.props.onUpdateClasse();
    }

    render () {
        const { role, developer, elem, nextClasses } = this.props;
        const { loadElemId } = this.state;

        // console.log(elem)

        // let liste = []; let nbAlreadyResponse = 0; let nbTmCours = 0;
        // if(elem.groups){
        //     let groups = GroupEleves.sortGroupEleves(elem.groups);
        //
        //     let byGroups = [], duplicate = [];
        //     groups.forEach(el => {
        //         if(!duplicate.includes(el.tmCours.id)){
        //             duplicate.push(el.tmCours.id);
        //
        //             byGroups.push({
        //                 tmCours: el.tmCours.id,
        //                 dayInt: el.tmCours.dayInt,
        //                 day: el.tmCours.day,
        //                 start: el.tmCours.start,
        //                 eleves: [el]
        //             })
        //         }else{
        //             let tmp = [];
        //             byGroups.forEach(grp => {
        //                 if(grp.tmCours === el.tmCours.id){
        //                     grp.eleves.push(el)
        //                 }
        //
        //                 tmp.push(grp);
        //             })
        //
        //             byGroups = tmp;
        //         }
        //     })
        //
        //     byGroups.sort(Sort.compareDayThenStart);
        //     byGroups.forEach((byGrp, index) => {
        //         nbTmCours++;
        //
        //         let listeEleves = [];
        //         byGrp.eleves.forEach(elv => {
        //
        //             elv.tmGroups.forEach(tmG => {
        //                 if(tmG.classeFrom.id === el.classe.id){
        //                     assignedLevel = tmG.classe.nameCycleLevel;
        //
        //                     if(tmG.assignation !== null){
        //                         assigned = 1;
        //                         assignedMsg = "N'a pas confirmé";
        //                         assignedLesson = tmG.assignation.lesson.slot.dayString + " de " + tmG.assignation.lesson.startShortString + " à " + tmG.assignation.lesson.endShortString;
        //                         if(teacher !== tmG.classe.teacher.id){
        //                             teacherName = tmG.classe.teacher.lastname + " " + tmG.classe.teacher.firstname;
        //                         }
        //                         if(tmG.assignation.isAccepted){
        //                             assigned = 2;
        //                             assignedMsg = "Place confirmée";
        //                         }
        //                     }else{
        //                         if(tmG.isRefused && assigned === 0){
        //                             assigned = 3;
        //                             assignedMsg = "Place refusée";
        //                             if(teacher !== tmG.classe.teacher.id){
        //                                 teacherName = tmG.classe.teacher.lastname + " " + tmG.classe.teacher.firstname;
        //                             }
        //                             if(tmG.assignationRefused){
        //                                 assignedLesson = tmG.assignationRefused.lesson.slot.dayString + " de " + tmG.assignationRefused.lesson.startShortString + " à " + tmG.assignationRefused.lesson.endShortString;
        //                             }
        //                         }
        //
        //                         if(tmG.isMultiple && assigned === 0){
        //                             assigned = 1;
        //                             assignedMsg = "N'a pas confirmé";
        //                             assignedLesson = "Choix donné à l'élève";
        //                             teacherName = "";
        //                         }
        //                     }
        //                 }
        //             })

        //         })
        //
        //         //**
        //         //**  TM_COURS
        //         //**
        //         let date = new Date();
        //         if(listeEleves.length !== 0){
        //             liste.push(<div className="tmCours" key={index}>
        //                 <div className="name">Année {date.getFullYear() - 1}/{date.getFullYear()} : {byGrp.day} {byGrp.start}</div>
        //                 <div className="eleves">{listeEleves}</div>
        //                 {developer === 1 && <div className="level-add-eleve">
        //                     <Button onClick={() => onOpenAside(el.classe, byGrp.tmCours, byGrp.eleves)}>Ajouter un élève</Button>
        //                 </div>}
        //             </div>)
        //         }
        //     })
        // }

        let eleve = elem.eleve;
        let classe = elem.classe;
        let classeTo = elem.classeTo;

        let status = null, actions = null;
        let statusDisabled = "";

        let assignedMsg = "", assignedLesson = "", teacherName = "";

        if(elem.status === 1 || elem.status === 2){
            teacherName = classe.teacher.id !== classeTo.teacher.id && classeTo.teacher.fullnameCivility;
        }

        switch (elem.renewAnswer) {
            case 1: // accept
                assignedMsg = "Place confirmée";
                // assignedLesson = tmG.assignation.lesson.slot.dayString + " de " + tmG.assignation.lesson.startShortString + " à " + tmG.assignation.lesson.endShortString;
                break;
            case 2: // refuse
                assignedMsg = "Place refusée";
                // if(tmG.assignationRefused){
                //     assignedLesson = tmG.assignationRefused.lesson.slot.dayString + " de " + tmG.assignationRefused.lesson.startShortString + " à " + tmG.assignationRefused.lesson.endShortString;
                // }
                break;
            case 3: // waiting
            case 4: // priority waiting
                assignedMsg = "N'a pas confirmé";
                assignedLesson = "Choix donné à l'élève";
                teacherName = "";
                break;
            case 5: // wait september
                assignedMsg = "Attend septembre";
                break;
            case 6: // asked
                assignedMsg = "N'a pas confirmé";
                // assignedLesson = tmG.assignation.lesson.slot.dayString + " de " + tmG.assignation.lesson.startShortString + " à " + tmG.assignation.lesson.endShortString;
                break;
            default:
                break;
        }

        ///
        /// Affichage des data
        ///

        if(elem.isSuspended || (elem.dispenseFm && elem.isFm) || elem.isFinal){
            statusDisabled = " eleve-status-disabled";
            if(elem.dispenseFm && elem.isFm){
                status = <StatusHtml icon="certificate">Dispensé de formation musicale</StatusHtml>
                assignedMsg = <div className="disabled">Dispensé</div>
                if(role === "admin"){
                    actions = <ButtonCustom loadElemId={loadElemId} eleve={eleve}>Annuler la dispense</ButtonCustom>
                }
            }else if(elem.isSuspended){
                status = <StatusHtml icon="disabled">Suspendu</StatusHtml>
                assignedMsg = <div className="disabled">Suspendu</div>
                if(role === "admin"){
                    actions = <ButtonCustom loadElemId={loadElemId} eleve={eleve}>Annuler la suspension</ButtonCustom>
                }
            }else if(elem.isFinal){
                status = <StatusHtml icon="disabled">Ne continue pas</StatusHtml>
                assignedMsg = <div className="disabled">Ne continue pas</div>
                actions = <ButtonCustom loadElemId={loadElemId} eleve={eleve}>Rétablir</ButtonCustom>
            }
        }else{

            status =(elem.status === 1 || elem.status === 2) ? <div className="status">
                <span className={"icon-" + (elem.status === 1 ? "dot" : "up-arrow")} /> {classeTo.nameCycleLevel}
                <div className="name-teacher">{teacherName}</div>
            </div> : "";

            actions = loadElemId === eleve.id ? <LoaderElement /> : (elem.status === 0 ? (nextClasses && <>
                {nextClasses.id !== classe.id && <div className="monter">Monter</div>}
                <div className="garder">Garder</div>
                <div className="stop">Ne continue pas</div>

                {role === "admin" && <div className="actions-admin">
                    {!elem.isGiven && <div className="donner">
                        <ButtonIcon tooltipDirection="bottom" icon="heart">
                            Déléguer
                        </ButtonIcon>
                    </div>}
                    <div className="disabled">
                        <ButtonIcon tooltipDirection="bottom" icon="disabled">
                            Suspendre
                        </ButtonIcon>
                    </div>
                    {elem.isFm && <div className="dispensed">
                        <ButtonIcon tooltipDirection="bottom" icon="certificate">
                            Dispenser
                        </ButtonIcon>
                    </div>}
                    {developer === 1 && elem.status === 0 && <div className="dispensed">
                        <ButtonIcon tooltipDirection="bottom" icon="trash">
                            Supprimer
                        </ButtonIcon>
                    </div>}
                </div>}
            </>) : (elem.renewAnswer !== 1 && <div className="modifier" onClick={(() => this.handleReset(elem.id, eleve))}>{elem.status === 3 ? "Récupérer" : "Modifier"}</div>))
        }

        let item = "";
        if(!elem.isGiven || (elem.isGiven && (role === "admin" || elem.status === 3)) ){
            item =  <div className={"eleve eleve-status-" + elem.status + statusDisabled } key={elem.id}>
                <div className="col-1 eleve-infos">
                    <div className="name">
                        <b className={elem.isGiven ? "given" : ""}>{elem.isGiven && <span className="icon-heart" />} {eleve.lastname}</b> {eleve.firstname}
                    </div>
                    <div className="age">{eleve.age} ans</div>
                </div>

                <div className="col-2 eleve-levels">
                    {status}
                </div>

                <div className="col-3 actions-level">
                    {actions}
                </div>

                <div className="col-4 eleve-place">
                    <div className={"eleve-place-status eleve-place-status-" + elem.renewAnswer}>{assignedMsg}</div>
                    <div>{assignedLesson}</div>
                </div>
            </div>
        }

        return <>{item}</>
    }
}

function StatusHtml ({ icon, children }) {
    return <div className="status"><span className={"icon-" + icon} /> {children}</div>
}

function ButtonCustom ({ loadElemId, eleve, children }) {
    return loadElemId === eleve.id ? <LoaderElement /> :
        <div className="disabled">{children}</div>
}