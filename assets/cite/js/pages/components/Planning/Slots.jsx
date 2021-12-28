import React, { Component } from "react";

import TimeHelper           from "@citeComponents/functions/timeHelper";
import Formulaire           from "@dashboardComponents/functions/Formulaire";
import Sort                 from "@commonComponents/functions/sort";

import { LoaderElement }    from "@dashboardComponents/Layout/Loader";
import { ButtonIcon }       from "@dashboardComponents/Tools/Button";

export class Slots extends Component {
    constructor(props) {
        super();

        this.handleMergeSlot = this.handleMergeSlot.bind(this);
    }

    componentDidMount = () => {
        this.handleMergeSlot();
    }

    componentDidUpdate = () => {
        this.handleMergeSlot();
    }

    handleMergeSlot = () => {
        let merges = document.querySelectorAll('.slot-merge')
        if(merges.length !== 0){
            Formulaire.loader(true)

            merges.forEach(elem => {
                let main = document.querySelector('.slot-' + elem.dataset.merge);
                let mainGroup = document.querySelector('.slot-' + elem.dataset.merge + ' > .group');
                let second = document.querySelector('.slot-' + elem.dataset.id);
                let group = document.querySelector('.slot-' + elem.dataset.id + ' > .group');

                main.classList.add("slot-merged")
                if(group){ // for display in homepage, group is display none
                    main.insertBefore(group, mainGroup);
                }
                second.style.display = "none";
            })
            Formulaire.loader(false)
        }
    }

    render () {
        const { developer, role, isOnInstru, isOnFm, dayActive, slots, onChangeContext } = this.props

        let items = [], previousEnd = "", previousStart = "", previousId = "", first=true;
        let data = slots;

        if(data){
            data.forEach(elem => {
                if(elem.day === dayActive){

                    // ---------------------------------------------------------
                    // ----------------- init values
                    // ---------------------------------------------------------
                    let activityName = elem.activity.name;
                    let cycleName = elem.cycle && elem.cycle.name;
                    let classroomName = elem.classroom ? elem.classroom.num + " " + elem.classroom.name.toLowerCase() : "Salle indéfinie";
                    let centreName = elem.center ? elem.center.name : "Centre indéfinie";
                    let levelName = elem.level && "- " + elem.level.name;

                    let start = elem.startString ? TimeHelper.betterDisplayTime(elem.startString) : 'Indéfinie';
                    let end = elem.endString ? TimeHelper.betterDisplayTime(elem.endString) : 'Indéfinie';

                    // ------ If merge slot
                    let classSlotMerge = ""; let slotMerge = false;
                    if(!first){
                        if(start === previousStart){
                            slotMerge = true;
                            classSlotMerge = " slot-merge";
                        }
                    }

                    //---------------------------------------------------------
                    //----------------- Lessons slots
                    //---------------------------------------------------------
                    // let lessons = [], last_end = null;
                    // if(elem.lessons){
                    //     let ls = [];
                    //     if(!Array.isArray(elem.lessons)){
                    //         let tab = Object.values(elem.lessons)
                    //         tab.forEach(el => { ls.push(el) })
                    //     }else{
                    //         ls = elem.lessons
                    //     }
                    //     ls.sort(TimeHelper.compareStartString)
                    //     ls.forEach((lesson, index) => {
                    //         let nextLesson = ls[index + 1] ? ls[index + 1] : null;
                    //         let prevLesson = ls[index - 1] ? ls[index - 1] : null;
                    //         last_end = lesson.endString
                    //
                    //         let canEdit = true;
                    //         if(elem.activity && role !== "admin"){
                    //             if(elem.activity.departement === "Formation musicale"){
                    //                 if(!isOnFm){
                    //                     canEdit = false;
                    //                 }
                    //             }else{
                    //                 if(!isOnInstru){
                    //                     canEdit = false;
                    //                 }
                    //             }
                    //         }
                    //
                    //         let assignations = lesson.assignations.sort(Sort.compareEleveLastname);
                    //         let nbAccepted = 0, nbValidatePay = 0;
                    //         if(lesson.assignations.length !== 0){
                    //             lesson.assignations.forEach(assi => {
                    //                 if(assi.isAccepted){
                    //                     nbAccepted++;
                    //                 }
                    //
                    //                 if(assi.eleve.canPay === 2){
                    //                     nbAccepted--;
                    //                     nbValidatePay++;
                    //                 }
                    //             })
                    //         }
                    //
                    //         let classMax = lesson.classe.max;
                    //         let assignationLength = lesson.assignations.length;
                    //
                    //         let total = <span className="total">
                    //             <div><span className="icon-user" /> {assignationLength} / {classMax}</div>
                    //             {nbAccepted > 0 && <div><span className="icon-time" /> {nbAccepted}</div>}
                    //             {nbValidatePay > 0 && <div><span className="icon-check" /> {nbValidatePay}</div>}
                    //         </span>
                    //
                    //         if(nbAccepted === classMax){
                    //             total = <span className="total">
                    //                 <div><span className="icon-time" /> {assignationLength} / {classMax}</div>
                    //                 {nbValidatePay > 0 && <div><span className="icon-check" /> {nbValidatePay}</div>}
                    //             </span>
                    //         }
                    //
                    //         if(nbValidatePay === classMax){
                    //             total = <span className="total">
                    //                 <div><span className="icon-check" /> {assignationLength} / {classMax}</div>
                    //                 {nbAccepted > 0 && <div><span className="icon-time" /> {nbAccepted}</div>}
                    //             </span>
                    //         }
                    //
                    //         lessons.push(<div className="lesson" key={lesson.id}>
                    //             <div className="lesson-details">
                    //                 <div className="time">
                    //                     <span>{TimeHelper.betterDisplayTime(lesson.startString)} à {TimeHelper.betterDisplayTime(lesson.endString)}</span>
                    //                     {role !== "manager" && canEdit && <div className="lesson-actions">
                    //                         {prevLesson && <ButtonIcon onClick={() => onReOrder(elem, lesson, prevLesson)} icon="up-arrow">Monter</ButtonIcon>}
                    //                         {nextLesson && <ButtonIcon onClick={() => onReOrder(elem, nextLesson, lesson)} icon="down-arrow">Descendre</ButtonIcon>}
                    //                         <ButtonIcon icon="pencil" onClick={() => onChangeContext("update", elem, lesson)}>Modifier</ButtonIcon>
                    //                         {nbAccepted === 0 && <ButtonIcon icon="trash" onClick={() => onDelete(elem, lesson)}>Supprimer</ButtonIcon>}
                    //                     </div>}
                    //                 </div>
                    //                 <div className="infos">
                    //                     <div className="lesson-infos">
                    //                         {parseInt(developer) === 1 &&  <div className="activity">#{lesson.id}</div>}
                    //                         <span className="activity">{lesson.nameCycleLevel}</span>
                    //                         <div className="lesson-assignations">
                    //                             {total}
                    //                             {lesson.assignations && <div className="lesson-infos-eleves">
                    //                                 <div className={"lesson-infos-eleves-container" + (lesson.assignations.length > 3 ? " expand" : "")}>
                    //                                     {assignations.map(el => {
                    //                                         console.log(el)
                    //                                         if(el.isSuspended){
                    //                                             return null
                    //                                         }else{
                    //                                             let eleve = el.eleve;
                    //
                    //                                             let icon = el.eleve.canPay === 2 ? "icon-check" : "icon-time";
                    //                                             if(!el.isAccepted){
                    //                                                 icon = "";
                    //                                             }
                    //
                    //                                             return <div key={el.id} className={"lesson-infos-eleves-name " + (el.isAccepted ? "eleve-checked" : "")}>
                    //                                                 <span><span className={icon}/>{eleve.lastname} {eleve.firstname}</span>
                    //                                                 {lesson.isMixte && <span className="details-classe">{el.classe.nameCycleLevel}</span>}
                    //                                             </div>
                    //                                         }
                    //                                     })}
                    //                                 </div>
                    //                             </div>}
                    //                         </div>
                    //                     </div>
                    //                 </div>
                    //             </div>
                    //         </div>)
                    //     })
                    // }

                    let add = <div className="group-add" onClick={() => onChangeContext("create", elem)}>
                        <div> <span className="icon-plus" /><span>Ajouter un cours</span> </div>
                    </div>

                    // if(last_end){
                    //     if(last_end >= end){
                    //         add = null
                    //     }
                    // }

                    // if(elem.activity && role !== "admin"){
                    //     if(elem.activity.departement === "Formation musicale"){
                    //         if(!isOnFm){
                    //             add = null
                    //         }
                    //     }else{
                    //         if(!isOnInstru){
                    //             add = null
                    //         }
                    //     }
                    // }
                    //
                    // if(role === "manager"){
                    //     add = null;
                    // }

                    // ---------------------------------------------------------
                    // ----------------- push render slot
                    // ---------------------------------------------------------
                    items.push(<div className={"slot slot-" + elem.id + classSlotMerge} data-merge={slotMerge ? previousId : ""} data-id={elem.id} key={elem.id}>

                        {start !== previousEnd ? <>
                            {slotMerge ? null : <div className="pause"><span>PAUSE</span></div> }
                            <div className="start">{start} </div>
                        </> : null}

                        <div className="group">
                            <div className="group-infos">
                                <div>
                                    <span className="centre">{centreName}</span>
                                    <span className="activity"> - {activityName === "FORMATION MUSICALE" ? "FM" : activityName} {cycleName} {levelName}</span>
                                </div>
                                {classroomName !== "Salle indéfinie" && <span className="classroom">[{classroomName}]</span>}
                            </div>
                            {/*{lessons.length !== 0 ? <div className="lessons">{lessons}</div> : null}*/}

                            {add}
                        </div>

                        <div className="end">{end}</div>
                    </div>)

                    // ---------------------------------------------------------
                    // ----------------- update values
                    // ---------------------------------------------------------
                    previousEnd = slotMerge ? previousEnd : end
                    previousStart = slotMerge ? previousStart : start
                    previousId = slotMerge ? previousId : elem.id // keep id first merge
                    first = false
                }
            })
        }

        let content = <LoaderElement />;
        if(data){
            if(items.length !== 0){
                content = <div className="slots">{items}</div>
            }else{
                content = <div>Rien de prévu ce jour la !</div>;
            }
        }

        return <>{content}</>
    }
}