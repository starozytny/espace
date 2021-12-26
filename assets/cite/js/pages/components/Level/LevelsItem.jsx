import React, { Component } from "react";

import { Alert }              from "@dashboardComponents/Tools/Alert";
import { LoaderElement }      from "@dashboardComponents/Layout/Loader";
import { Button, ButtonIcon } from "@dashboardComponents/Tools/Button";

export class LevelsItem extends Component {
    render () {
        const { elem } = this.props;

        let items = classes.map(el => {

            if(el.classe.centre.id === centre){
                if(elem.groups.length > 0){
                    // ------------------- CLASSE ACTUELLE ---------------
                    return <div key={elem.id}>
                        {elem.groups.length >= 0 && <div className={"classe" + activeClasse + full}>
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
                        </div>}
                    </div>
                }
            }
        })

        return <>{items.length !== 0 ? <>{items}</> : <Alert type="default">Vous n'avez aucun élève.</Alert>}</>
    }
}