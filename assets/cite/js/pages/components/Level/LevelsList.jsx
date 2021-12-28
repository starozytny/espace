import React, { Component } from 'react';

import { Alert } from "@dashboardComponents/Tools/Alert";

import { LevelsItem } from "./LevelsItem";

export class LevelsList extends Component {
    constructor(props) {
        super();

        this.state = {
            classe: null,
            choiceActive: null
        }

        this.handleClickClasse = this.handleClickClasse.bind(this);
        this.handleSelect = this.handleSelect.bind(this);
    }

    handleClickClasse = (classe) => {
        this.setState((prevState) => {
            return { classe: prevState.classe === classe ? null : classe }
        })
    }

    handleSelect = (choice) => {
        this.setState({ choiceActive: choice })
    }

    render () {
        const { role, developer, classes, center, onUpdateClasse } = this.props;
        const { classe, choiceActive } = this.state;

        let data = classes;

        return <>
            <div>
                <div className="items-table">
                    {data && data.length !== 0 ? data.map(el => {

                        let elem = el.classe;
                        if(elem.center.id === center){

                            ///
                            /// Affichage de la/les classes supérieures
                            ///

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

                            ///
                            /// Affichage des choix quand il y a plusieurs possibilités de classes supérieures.
                            ///

                            let choices = [], noDuplication = [], choicesAdmin = [], possibilities = [];
                            if(multiple){
                                nextClasses.classes.forEach(el => {
                                    if(!noDuplication.includes(el.name)){
                                        noDuplication.push(el.name)
                                    }
                                })

                                // général choice

                                noDuplication.forEach((choice, index) => {
                                    choices.push(<div className={"choice choice-select" + (choiceActive === choice ? " active" : "")} onClick={() => this.handleSelect(choice)} key={index}>
                                        <div className="infos">
                                            <div className="infos-title">{choice}</div>
                                        </div>
                                    </div>)
                                })

                                // multiple choice with teacher

                                nextClasses.classes.forEach((choice, index) => {
                                    //display possibilities = no action so admin and user
                                    possibilities.push(<div className="choice" key={index}>
                                        <div className="infos">
                                            <div className="infos-title">[{choice.center.name}] {choice.nameCycleLevel}</div>
                                            <div className="total">
                                                <span>{choice.teacher.fullnameCivility}</span>
                                            </div>
                                        </div>
                                    </div>)

                                    //display choice = action possible so admin only
                                    choicesAdmin.push(<div className={"choice choice-select" + (choiceActive === choice ? " active" : "")} onClick={() => this.handleSelect(choice)} key={index}>
                                        <div className="infos">
                                            <div className="infos-title">[{choice.center.name}] {choice.nameCycleLevel}</div>
                                            <div className="total">
                                                <span>{choice.teacher.fullnameCivility}</span>
                                            </div>
                                        </div>
                                    </div>)
                                })
                            }


                            ///
                            /// Calcul du nombre d'élèves traités
                            ///

                            let nbInProcessing = 0;
                            elem.groups.forEach(elv => {
                                if(elv.status !== 0){ nbInProcessing++; }
                            })

                            let full = (elem.groups.length === nbInProcessing) ? " full" : ""

                            ///
                            /// Affichage de la box classe
                            ///

                            let activeClasse = (classe && classe.id === elem.id) ? " active" : "";

                            return <div key={elem.id}>
                                <div className={"classe" + full}>
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
                                                <span className="icon-group" /> {nbInProcessing}/{elem.groups.length}
                                            </div>
                                        </div>
                                        {next}
                                    </div>
                                    <div className="classe-eleves">
                                        {role !== "admin" && possibilities.length !== 0 && <div className="choice-classe choice-possibilities">
                                            <p className="title-orientation">Possibilités pour le niveau supérieur : </p>
                                            {possibilities}
                                        </div>}
                                        {role === "admin" && choicesAdmin.length !== 0 && <>
                                            <p className="title-orientation">[ADMINISTRATEUR] Orientation spécifique: </p>
                                            <p>En utilisant cette option, le professeur sélectionné devra gérer l'élève via son espace.</p>
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
                                        <div className="liste liste-1">
                                            <div className="name name-header">
                                                <div className="col-1">Elève</div>
                                                <div className="col-2">Niveau l'année prochaine</div>
                                                <div className="col-3">Actions l'année prochaine</div>
                                                <div className="col-4">Etat de la proposition</div>
                                            </div>
                                            {(elem.groups && elem.groups.length !== 0) ? elem.groups.map((grp, index) => {
                                                return <div className="tmCours" key={index}>
                                                    <div className="name">Année</div>
                                                    <div className="eleves"><LevelsItem role={role} developer={developer} key={index}
                                                                                        elem={grp} nextClasse={nextClasses}
                                                                                        onUpdateClasse={onUpdateClasse}/></div>
                                                </div>
                                            }) : <Alert>Aucun élève dans cette classe.</Alert>}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        }
                    }) : <Alert>Aucun résultat</Alert>}
                </div>
            </div>
        </>
    }
}