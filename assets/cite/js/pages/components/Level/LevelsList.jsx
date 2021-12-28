import React, { Component } from 'react';

import { Alert } from "@dashboardComponents/Tools/Alert";
import {LevelsItem} from "./LevelsItem";

export class LevelsList extends Component {
    constructor(props) {
        super();

        this.state = {
            classe: null
        }

        this.handleClickClasse = this.handleClickClasse.bind(this);
    }

    handleClickClasse = (classe) => {
        this.setState((prevState) => {
            return { classe: prevState.classe === classe ? null : classe }
        })
    }

    render () {
        const { classes, center } = this.props;
        const { classe } = this.state;

        let data = classes;

        return <>
            <div>
                <div className="items-table">
                    {data && data.length !== 0 ? data.map(el => {

                        let elem = el.classe;
                        if(elem.center.id === center){
                            let nextClasses, multiple = false;

                            if(el.up.length === 1){
                                nextClasses = el.up[0];
                            }else if(el.up.length > 1){
                                multiple = true;
                                nextClasses = {
                                    'name': el.up.length + " professeurs possibles.",
                                    'classes': el.up
                                }
                            }

                            let next = <>
                                <div className="separator">
                                    <span className="icon-right-arrow" />
                                </div>
                                <div className="infos-next">
                                    {!nextClasses ?
                                        <div className="txt-danger">Erreur, veuillez contacter le support.</div>
                                        : (!multiple ? <>
                                            <div
                                                className="infos-title">{nextClasses.center.id !== center && nextClasses.center.name} {nextClasses.name}</div>
                                            <span className="total">
                                                <span>{nextClasses.teacher.fullnameCivility}</span>
                                            </span>
                                        </> : <div className="infos-title">{nextClasses.name}</div>)
                                    }
                                </div>
                            </>

                            // let full = (elem.groups.length === nbAlreadyResponse) ? " full" : ""
                            //
                            // // ----------------- DISPLAY NEXT WITH X TEACHERS ----------------------
                            // let choices = [], choicesAdmin = []; let duplicate = []; let possibilities = [];
                            // if(haveLevelUp && multiple){
                            //     next.classes.forEach(el => {
                            //         if(!duplicate.includes(el.name)){
                            //             duplicate.push(el.name)
                            //         }
                            //     })
                            //
                            //     duplicate.forEach((choice, index) => {
                            //         choices.push(<div className={"choice choice-select" + (choiceActive === choice ? " active" : "")} onClick={() => onSelectChoice(choice)} key={index}>
                            //             <div className="infos">
                            //                 <div className="infos-title">{choice}</div>
                            //             </div>
                            //         </div>)
                            //     })
                            //
                            //     next.classes.forEach(choice => {
                            //         possibilities.push(<div className="choice" key={choice.id}>
                            //             <div className="infos">
                            //                 <div className="infos-title">[{choice.centre.name}] {choice.nameCycleLevel}</div>
                            //                 <div className="total">
                            //                     <span>{choice.teacher ? choice.teacher.civility + ". " + choice.teacher.lastname + " " + choice.teacher.firstname : null }</span>
                            //                 </div>
                            //             </div>
                            //         </div>)
                            //
                            //         choicesAdmin.push(<div className={"choice choice-select" + (choiceActive === choice ? " active" : "")} onClick={() => onSelectChoice(choice)} key={choice.id}>
                            //             <div className="infos">
                            //                 <div className="infos-title">[{choice.centre.name}] {choice.nameCycleLevel}</div>
                            //                 <div className="total">
                            //                     <span>{choice.teacher ? choice.teacher.civility + ". " + choice.teacher.lastname + " " + choice.teacher.firstname : null }</span>
                            //                 </div>
                            //             </div>
                            //         </div>)
                            //     })
                            // }

                            let activeClasse = (classe && classe.id === elem.id) ? " active" : "";

                            return <div key={elem.id}>
                                <div className="classe">
                                    <div className={"classe-infos" + activeClasse} onClick={() => this.handleClickClasse(elem)}>
                                        <div className="radio">
                                            <div className="radio-button"/>
                                        </div>
                                        <div className="infos">
                                            <div className="infos-title">{elem.name}</div>
                                            <span className="total">
                                                <span>{elem.teacher.fullnameCivility}</span>
                                            </span>
                                            <div className="total">
                                                <span className="icon-group" /> {elem.groupes.length}
                                            </div>
                                        </div>
                                        {next}
                                    </div>
                                    <div className="classe-eleves">
                                        <div className="liste liste-1">
                                            <div className="name name-header">
                                                <div className="col-1">Elève</div>
                                                <div className="col-2">Niveau l'année prochaine</div>
                                                <div className="col-3">Actions l'année prochaine</div>
                                                <div className="col-4">Etat de la proposition</div>
                                            </div>
                                            {(elem.groupes && elem.groupes.length !== 0) ? elem.groupes.map(el => {
                                                return <LevelsItem elem={el}/>
                                            }) : <Alert>Aucun élève dans cette classe.</Alert>}
                                        </div>
                                    </div>
                                </div>
                                {/*<div className={"classe" + activeClasse + full}>*/}
                                {/*    <div className={"classe-infos" + activeClasse} onClick={() => onSelectClasse(elem)}>*/}
                                {/*        <div className="radio">*/}
                                {/*            <div className="radio-button"/>*/}
                                {/*        </div>*/}
                                {/*        <div className="infos">*/}
                                {/*            <div className="infos-title">{elem.name}</div>*/}
                                {/*            <span className="total">*/}
                                {/*                <span>{elem.teacher ? elem.teacher.civility + ". " + elem.teacher.lastname + " " + elem.teacher.firstname : null }</span>*/}
                                {/*            </span>*/}
                                {/*            <div className="total">*/}
                                {/*                <span className="icon-user" />{nbAlreadyResponse}/{elem.groups.length}*/}
                                {/*            </div>*/}
                                {/*        </div>*/}
                                {/*        {htmlNext}*/}
                                {/*    </div>*/}
                                {/*    <div className="classe-eleves">*/}
                                {/*        {role !== "admin" && possibilities.length !== 0 && <div className="choice-classe choice-possibilities">*/}
                                {/*            <p className="title-orientation">Liste des possibilités qu'aura l'élève pour le niveau supérieur : </p>*/}
                                {/*            {possibilities}*/}
                                {/*        </div>}*/}
                                {/*        {role === "admin" && choicesAdmin.length !== 0 && <>*/}
                                {/*            <p className="title-orientation">Orientation Admin: </p>*/}
                                {/*            <p>En utilisant cette option, le professeur désigné devra placer l'élève dans un cours pour qu'il ait une proposition.</p>*/}
                                {/*            <div className="choice-classe">*/}
                                {/*                {choicesAdmin}*/}
                                {/*            </div>*/}
                                {/*        </>}*/}
                                {/*        {choices.length !== 0 && <>*/}
                                {/*            <p className="title-orientation">Orientation pour le niveau supérieur : </p>*/}
                                {/*            <div className="choice-classe">*/}
                                {/*                {choices}*/}
                                {/*            </div>*/}
                                {/*        </>}*/}
                                {/*        <div className={"liste liste-" + (nbTmCours > 1 ? "2" : "1")}>*/}
                                {/*            <div className="name name-header">*/}
                                {/*                <div className="col-1">Elève</div>*/}
                                {/*                <div className="col-2">Niveau l'année prochaine</div>*/}
                                {/*                <div className="col-3">Actions l'année prochaine</div>*/}
                                {/*                <div className="col-4">Etat de la proposition</div>*/}
                                {/*            </div>*/}
                                {/*            {liste}*/}
                                {/*        </div>*/}
                                {/*    </div>*/}
                                {/*</div>*/}
                            </div>
                        }
                    }) : <Alert>Aucun résultat</Alert>}
                </div>
            </div>
        </>
    }
}