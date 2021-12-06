import React, { Component } from "react";

import GroupEleves            from "@citeComponents/functions/groupEleves";
import Sort                   from "@commonComponents/functions/sort";
import { Alert }              from "@dashboardComponents/Tools/Alert";
import { LoaderElement }      from "@dashboardComponents/Layout/Loader";
import { Button, ButtonIcon } from "@dashboardComponents/Tools/Button";

export class ClasseItem extends Component {
    render () {
        const { developer, role, classes, classe, onSelectClasse, onLevelUp, onStay, onEdit, loadElem, onGive, onSelectChoice,
            choiceActive, centre, onChangeSuspended, onChangeDispensed, onOpenAside, onDelete, onFinal } = this.props;

        let items = classes.map(el => {

            if(el.classe.centre.id === centre){
                let next = null; let multiple = false;
                let elem, haveLevelUp = false;
                if(el.up){
                    haveLevelUp = true;
                    elem = el.classe;
                    if(el.up.length === 1){
                        next = el.up[0];
                    }else if(el.up.length > 1){
                        multiple = true;
                        next = {
                            'name': el.up.length + " enseignants.",
                            'classes': el.up
                        }
                    }
                }else{
                    elem = el
                }

                // -------------------- CLASSE N+1 ------------
                let htmlNext = "";
                if(haveLevelUp){
                    if(!multiple && elem.id === next.id){
                    }else{
                        htmlNext = <>
                            <div className="separator">
                                <span className="icon-right-arrow" />
                            </div>
                            <div className="infos-next">
                                {next && <>
                                    <div className="infos-title">{next.centre && next.centre.id !== centre && "[" + next.centre.name + "]"} {next.name}</div>
                                    <span className="total">
                                    <span>{(next.teacher && next.teacher.lastname !== el.classe.teacher.lastname) ? next.teacher.civility + ". " + next.teacher.lastname + " " + next.teacher.firstname : null }</span>
                                </span>
                                </>}
                                {!next && <div className="txt-danger">Erreur, veuillez contacter le support.</div>}
                            </div>
                        </>
                    }
                }

                // --------------- LISTE DES ELEVES -----------------
                let liste = []; let nbAlreadyResponse = 0; let nbTmCours = 0;
                if(elem.groups){
                    let groups = GroupEleves.sortGroupEleves(elem.groups);

                    let byGroups = [], duplicate = [];
                    groups.forEach(el => {
                        if(!duplicate.includes(el.tmCours.id)){
                            duplicate.push(el.tmCours.id);

                            byGroups.push({
                                tmCours: el.tmCours.id,
                                dayInt: el.tmCours.dayInt,
                                day: el.tmCours.day,
                                start: el.tmCours.start,
                                eleves: [el]
                            })
                        }else{
                            let tmp = [];
                            byGroups.forEach(grp => {
                                if(grp.tmCours === el.tmCours.id){
                                    grp.eleves.push(el)
                                }

                                tmp.push(grp);
                            })

                            byGroups = tmp;
                        }
                    })

                    byGroups.sort(Sort.compareDayThenStart);
                    byGroups.forEach((byGrp, index) => {
                        nbTmCours++;

                        let listeEleves = [];
                        byGrp.eleves.forEach(elv => {

                            let assigned = 0;
                            let assignedMsg = "";
                            let assignedLesson = "";
                            let assignedLevel = "";
                            let status = "", statusDisabled = "", actions = "";
                            let teacher = el.classe.teacher.id;
                            let teacherName = "";

                            elv.tmGroups.forEach(tmG => {
                                if(tmG.classeFrom.id === el.classe.id){
                                    assignedLevel = tmG.classe.nameCycleLevel;

                                    if(tmG.assignation !== null){
                                        assigned = 1;
                                        assignedMsg = "N'a pas confirmé";
                                        assignedLesson = tmG.assignation.lesson.slot.dayString + " de " + tmG.assignation.lesson.startShortString + " à " + tmG.assignation.lesson.endShortString;
                                        if(teacher !== tmG.classe.teacher.id){
                                            teacherName = tmG.classe.teacher.lastname + " " + tmG.classe.teacher.firstname;
                                        }
                                        if(tmG.assignation.isAccepted){
                                            assigned = 2;
                                            assignedMsg = elv.canPay === 2 ? "Place payée" : "Place confirmée";
                                        }
                                    }else{
                                        if(tmG.isRefused && assigned === 0){
                                            assigned = 3;
                                            assignedMsg = elv.renewAnswer === 5 ? "Attend sept." : "Place refusée";
                                            if(teacher !== tmG.classe.teacher.id){
                                                teacherName = tmG.classe.teacher.lastname + " " + tmG.classe.teacher.firstname;
                                            }
                                            if(tmG.assignationRefused){
                                                assignedLesson = tmG.assignationRefused.lesson.slot.dayString + " de " + tmG.assignationRefused.lesson.startShortString + " à " + tmG.assignationRefused.lesson.endShortString;
                                            }
                                        }

                                        if(tmG.isMultiple && assigned === 0){
                                            assigned = 1;
                                            assignedMsg = "N'a pas confirmé";
                                            assignedLesson = "Choix donné à l'élève";
                                            teacherName = "";
                                        }else if(!tmG.isMultiple && assigned === 0){
                                            if(teacher !== tmG.classe.teacher.id){
                                                teacherName = tmG.classe.teacher.lastname + " " + tmG.classe.teacher.firstname;
                                            }
                                        }
                                    }
                                }
                            })

                            if(haveLevelUp){
                                if(((elv.isFm && !elv.dispenseFm) || !elv.isFm) && !elv.isSuspended && !elv.isFinal){

                                    status =(elv.status === 1 || elv.status === 2) ? <div className="status">
                                        {elv.status === 1 ? <><span className="icon-dot" />  {assignedLevel}</> : <><span className="icon-up-arrow" /> {assignedLevel}</>}
                                        <div className="name-teacher">{teacherName}</div>
                                    </div> : "";

                                    actions = loadElem === elv.id ? <LoaderElement /> : (elv.status === 0 ? (next && <>

                                        {next && next.id !== el.classe.id && <div className="monter" onClick={(() => onLevelUp(elv.idGroup, elv, next, classe))}>Monter</div>}
                                        <div className="garder" onClick={(() => onStay(elv.idGroup, elv, classe, classe))}>Garder</div>
                                        <div className="stop" onClick={(() => onFinal(elv.idGroup, elv, classe))}>Ne continue pas</div>

                                        {role === "admin" && <div className="actions-admin">
                                            {!elv.isGiven && <div className="donner">
                                                <ButtonIcon tooltipDirection="bottom" icon="heart" onClick={() => onGive(elv.idGroup, elv, classe, classe)}>
                                                    Déléguer
                                                </ButtonIcon>
                                            </div>}
                                            <div className="disabled">
                                                <ButtonIcon tooltipDirection="bottom" icon="disabled" onClick={() => onChangeSuspended(elv.idGroup, elv)}>
                                                    Suspendre
                                                </ButtonIcon>
                                            </div>
                                            {elv.isFm && <div className="dispensed">
                                                <ButtonIcon tooltipDirection="bottom" icon="certificate" onClick={() => onChangeDispensed(elv.idGroup, elv)}>
                                                    Dispenser
                                                </ButtonIcon>
                                            </div>}
                                            {developer === 1 && elv.status === 0 && <div className="dispensed">
                                                <ButtonIcon tooltipDirection="bottom" icon="trash" onClick={() => onDelete(elv.idGroup, elv)}>
                                                    Supprimer
                                                </ButtonIcon>
                                            </div>}
                                        </div>}

                                    </>) : (assigned !== 2 && <div className="modifier" onClick={(() => onEdit(elv.idGroup, elv, next, classe))}>{elv.status === 3 ? "Récuperer" : "Modifier"}</div>))
                                }else{
                                    statusDisabled = " eleve-status-disabled";
                                    if(elv.dispenseFm && elv.isFm){
                                        status = <div className="status"><span className="icon-certificate" />Dispensé de formation musicale</div>
                                        assignedMsg = <div className="disabled">Dispensé</div>
                                        if(role === "admin"){
                                            actions = loadElem === elv.id ? <LoaderElement /> :
                                                <div className="disabled" onClick={(() => onChangeDispensed(elv.idGroup, elv))}>Annuler la dispense</div>
                                        }
                                    }else if(elv.isSuspended){
                                        status = <div className="status"><span className="icon-disabled" /> Suspendu</div>
                                        assignedMsg = <div className="disabled">Suspendu</div>
                                        if(role === "admin"){
                                            actions = loadElem === elv.id ? <LoaderElement /> :
                                                <div className="disabled" onClick={(() => onChangeSuspended(elv.idGroup, elv))}>Annuler la suspension</div>
                                        }
                                    }else if(elv.isFinal){
                                        status = <div className="status"><span className="icon-disabled" /> Ne continue pas</div>
                                        assignedMsg = <div className="disabled">Ne continue pas</div>
                                        actions = loadElem === elv.id ? <LoaderElement /> :
                                            <div className="disabled" onClick={(() => onFinal(elv.idGroup, elv, classe))}>Rétablir</div>
                                    }
                                }
                            }

                            if(elv.status !== 0 || elv.dispenseFm || elv.isSuspended || elv.isFinal){
                                nbAlreadyResponse++;
                            }

                            //**
                            //**  GIVEN
                            //**
                            if(!elv.isGiven || (elv.isGiven && (role === "admin" || elv.status === 3)) ){
                                listeEleves.push(<div className={"eleve eleve-status-" + elv.status + statusDisabled } key={elv.id}>
                                    <div className="col-1 eleve-infos">
                                        <div className="name">
                                            <b className={elv.isGiven ? "given" : ""}>{elv.isGiven && <span className="icon-heart" />} {elv.lastname}</b> {elv.firstname}
                                        </div>
                                        <div className="age">{elv.age} ans</div>

                                    </div>

                                    <div className="col-2 eleve-levels">
                                        {status}
                                    </div>

                                    <div className="col-3 actions-level">
                                        {actions}
                                    </div>

                                    <div className="col-4 eleve-place">
                                        <div className={"eleve-place-status eleve-place-status-" + assigned}>{assignedMsg}</div>
                                        <div>{assignedLesson}</div>
                                    </div>
                                </div>)
                            }
                        })

                        //**
                        //**  TM_COURS
                        //**
                        let date = new Date();
                        if(listeEleves.length !== 0){
                            liste.push(<div className="tmCours" key={index}>
                                <div className="name">Année {date.getFullYear() - 1}/{date.getFullYear()} : {byGrp.day} {byGrp.start}</div>
                                <div className="eleves">{listeEleves}</div>
                                {developer === 1 && <div className="level-add-eleve">
                                    <Button onClick={() => onOpenAside(el.classe, byGrp.tmCours, byGrp.eleves)}>Ajouter un élève</Button>
                                </div>}
                            </div>)
                        }
                    })
                }

                let activeClasse = (classe && classe.id === elem.id) ? " active" : "";
                let full = (elem.groups.length === nbAlreadyResponse) ? " full" : ""

                // ----------------- DISPLAY NEXT WITH X TEACHERS ----------------------
                let choices = [], choicesAdmin = []; let duplicate = []; let possibilities = [];
                if(haveLevelUp && multiple){
                    next.classes.forEach(el => {
                        if(!duplicate.includes(el.name)){
                            duplicate.push(el.name)
                        }
                    })

                    duplicate.forEach((choice, index) => {
                        choices.push(<div className={"choice choice-select" + (choiceActive === choice ? " active" : "")} onClick={() => onSelectChoice(choice)} key={index}>
                            <div className="infos">
                                <div className="infos-title">{choice}</div>
                            </div>
                        </div>)
                    })

                    next.classes.forEach(choice => {
                        possibilities.push(<div className="choice" key={choice.id}>
                            <div className="infos">
                                <div className="infos-title">[{choice.centre.name}] {choice.nameCycleLevel}</div>
                                <div className="total">
                                    <span>{choice.teacher ? choice.teacher.civility + ". " + choice.teacher.lastname + " " + choice.teacher.firstname : null }</span>
                                </div>
                            </div>
                        </div>)

                        choicesAdmin.push(<div className={"choice choice-select" + (choiceActive === choice ? " active" : "")} onClick={() => onSelectChoice(choice)} key={choice.id}>
                            <div className="infos">
                                <div className="infos-title">[{choice.centre.name}] {choice.nameCycleLevel}</div>
                                <div className="total">
                                    <span>{choice.teacher ? choice.teacher.civility + ". " + choice.teacher.lastname + " " + choice.teacher.firstname : null }</span>
                                </div>
                            </div>
                        </div>)
                    })
                }

                // ------------------- CLASSE ACTUELLE ---------------
                return <div key={elem.id}>
                    <div className={"classe" + activeClasse + full}>
                        <div className={"classe-infos" + activeClasse} onClick={() => onSelectClasse(elem)}>
                            <div className="radio">
                                <div className="radio-button"/>
                            </div>
                            <div className="infos">
                                <div className="infos-title">{elem.name}</div>
                                <span className="total">
                                <span>{elem.teacher ? elem.teacher.civility + ". " + elem.teacher.lastname + " " + elem.teacher.firstname : null }</span>
                            </span>
                                <div className="total">
                                    <span className="icon-user" />{nbAlreadyResponse}/{elem.groups.length}
                                </div>
                            </div>
                            {htmlNext}
                        </div>
                        <div className="classe-eleves">
                            {role !== "admin" && possibilities.length !== 0 && <div className="choice-classe choice-possibilities">
                                <p className="title-orientation">Liste des possibilités qu'aura l'élève pour le niveau supérieur : </p>
                                {possibilities}
                            </div>}
                            {role === "admin" && choicesAdmin.length !== 0 && <>
                                <p className="title-orientation">Orientation Admin: </p>
                                <p>En utilisant cette option, le professeur désigné devra placer l'élève dans un cours pour qu'il ait une proposition.</p>
                                <div className="choice-classe">
                                    {choicesAdmin}
                                </div>
                            </>}
                            {choices.length !== 0 && <>
                                <p className="title-orientation">Orientation pour le niveau supérieur : </p>
                                <div className="choice-classe">
                                    {choices}
                                </div>
                            </>}
                            <div className={"liste liste-" + (nbTmCours > 1 ? "2" : "1")}>
                                <div className="name name-header">
                                    <div className="col-1">Elève</div>
                                    <div className="col-2">Niveau l'année prochaine</div>
                                    <div className="col-3">Actions l'année prochaine</div>
                                    <div className="col-4">Etat de la proposition</div>
                                </div>
                                {liste}
                            </div>
                        </div>
                    </div>
                </div>
            }
        })

        return <>{items.length !== 0 ? <>{items}</> : <Alert type="default">Vous n'avez aucun élève.</Alert>}</>
    }
}