import React, { Component } from "react";

import { Alert }              from "@dashboardComponents/Tools/Alert";
import { LoaderElement }      from "@dashboardComponents/Layout/Loader";
import { Button, ButtonIcon } from "@dashboardComponents/Tools/Button";

export class LevelsItem extends Component {
    render () {
        const { elem } = this.props;

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
        //             let assigned = 0;
        //             let assignedMsg = "";
        //             let assignedLesson = "";
        //             let assignedLevel = "";
        //             let status = "", statusDisabled = "", actions = "";
        //             let teacher = el.classe.teacher.id;
        //             let teacherName = "";
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
        //
        //             if(haveLevelUp){
        //                 if(((elv.isFm && !elv.dispenseFm) || !elv.isFm) && !elv.isSuspended && !elv.isFinal){
        //
        //                     status =(elv.status === 1 || elv.status === 2) ? <div className="status">
        //                         {elv.status === 1 ? <><span className="icon-dot" />  {assignedLevel}</> : <><span className="icon-up-arrow" /> {assignedLevel}</>}
        //                         <div className="name-teacher">{teacherName}</div>
        //                     </div> : "";
        //
        //                     actions = loadElem === elv.id ? <LoaderElement /> : (elv.status === 0 ? (next && <>
        //
        //                         {next && next.id !== el.classe.id && <div className="monter" onClick={(() => onLevelUp(elv.idGroup, elv, next, classe))}>Monter</div>}
        //                         <div className="garder" onClick={(() => onStay(elv.idGroup, elv, classe, classe))}>Garder</div>
        //                         <div className="stop" onClick={(() => onFinal(elv.idGroup, elv, classe))}>Ne continue pas</div>
        //
        //                         {role === "admin" && <div className="actions-admin">
        //                             {!elv.isGiven && <div className="donner">
        //                                 <ButtonIcon tooltipDirection="bottom" icon="heart" onClick={() => onGive(elv.idGroup, elv, classe, classe)}>
        //                                     Déléguer
        //                                 </ButtonIcon>
        //                             </div>}
        //                             <div className="disabled">
        //                                 <ButtonIcon tooltipDirection="bottom" icon="disabled" onClick={() => onChangeSuspended(elv.idGroup, elv)}>
        //                                     Suspendre
        //                                 </ButtonIcon>
        //                             </div>
        //                             {elv.isFm && <div className="dispensed">
        //                                 <ButtonIcon tooltipDirection="bottom" icon="certificate" onClick={() => onChangeDispensed(elv.idGroup, elv)}>
        //                                     Dispenser
        //                                 </ButtonIcon>
        //                             </div>}
        //                             {developer === 1 && elv.status === 0 && <div className="dispensed">
        //                                 <ButtonIcon tooltipDirection="bottom" icon="trash" onClick={() => onDelete(elv.idGroup, elv)}>
        //                                     Supprimer
        //                                 </ButtonIcon>
        //                             </div>}
        //                         </div>}
        //
        //                     </>) : (assigned !== 2 && <div className="modifier" onClick={(() => onEdit(elv.idGroup, elv, next, classe))}>{elv.status === 3 ? "Récuperer" : "Modifier"}</div>))
        //                 }else{
        //                     statusDisabled = " eleve-status-disabled";
        //                     if(elv.dispenseFm && elv.isFm){
        //                         status = <div className="status"><span className="icon-certificate" />Dispensé de formation musicale</div>
        //                         assignedMsg = <div className="disabled">Dispensé</div>
        //                         if(role === "admin"){
        //                             actions = loadElem === elv.id ? <LoaderElement /> :
        //                                 <div className="disabled" onClick={(() => onChangeDispensed(elv.idGroup, elv))}>Annuler la dispense</div>
        //                         }
        //                     }else if(elv.isSuspended){
        //                         status = <div className="status"><span className="icon-disabled" /> Suspendu</div>
        //                         assignedMsg = <div className="disabled">Suspendu</div>
        //                         if(role === "admin"){
        //                             actions = loadElem === elv.id ? <LoaderElement /> :
        //                                 <div className="disabled" onClick={(() => onChangeSuspended(elv.idGroup, elv))}>Annuler la suspension</div>
        //                         }
        //                     }else if(elv.isFinal){
        //                         status = <div className="status"><span className="icon-disabled" /> Ne continue pas</div>
        //                         assignedMsg = <div className="disabled">Ne continue pas</div>
        //                         actions = loadElem === elv.id ? <LoaderElement /> :
        //                             <div className="disabled" onClick={(() => onFinal(elv.idGroup, elv, classe))}>Rétablir</div>
        //                     }
        //                 }
        //             }
        //
        //             if(elv.status !== 0 || elv.dispenseFm || elv.isSuspended || elv.isFinal){
        //                 nbAlreadyResponse++;
        //             }
        //
        //             //**
        //             //**  GIVEN
        //             //**
        //             if(!elv.isGiven || (elv.isGiven && (role === "admin" || elv.status === 3)) ){
        //                 listeEleves.push(<div className={"eleve eleve-status-" + elv.status + statusDisabled } key={elv.id}>
        //                     <div className="col-1 eleve-infos">
        //                         <div className="name">
        //                             <b className={elv.isGiven ? "given" : ""}>{elv.isGiven && <span className="icon-heart" />} {elv.lastname}</b> {elv.firstname}
        //                         </div>
        //                         <div className="age">{elv.age} ans</div>
        //
        //                     </div>
        //
        //                     <div className="col-2 eleve-levels">
        //                         {status}
        //                     </div>
        //
        //                     <div className="col-3 actions-level">
        //                         {actions}
        //                     </div>
        //
        //                     <div className="col-4 eleve-place">
        //                         <div className={"eleve-place-status eleve-place-status-" + assigned}>{assignedMsg}</div>
        //                         <div>{assignedLesson}</div>
        //                     </div>
        //                 </div>)
        //             }
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

        return <>
            <div className="tmCours">
                <div className={"eleve eleve-status-" + elem.status } key={elem.id}>
                    <div className="col-1 eleve-infos">
                        <div className="name">
                            <b className={elem.isGiven ? "given" : ""}>{elem.isGiven && <span className="icon-heart" />} {eleve.lastname}</b> {eleve.firstname}
                        </div>
                        <div className="age">{eleve.age} ans</div>

                    </div>

                    <div className="col-2 eleve-levels">

                    </div>

                    <div className="col-3 actions-level">

                    </div>

                    <div className="col-4 eleve-place">
                        <div className={"eleve-place-status eleve-place-status-"}></div>
                        <div></div>
                    </div>
                </div>
            </div>
        </>
    }
}