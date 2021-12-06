import React, { Component } from 'react';

import axios             from "axios";
import Routing           from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { LoaderElement } from "@dashboardComponents/Layout/Loader";
import Formulaire        from "@dashboardComponents/functions/Formulaire";

import { ClasseItem }    from "./ClasseItem";

function runChange(self, role, url, elv, method="GET")
{
    if(role === "admin"){
        self.props.onLoadElem(elv.id);
        axios({ method: method, url: url })
            .then(function (response) {
                let resp = response.data;
                self.props.onUpdateClasse(JSON.parse(resp.classe), null, resp.tmGroups, JSON.parse(resp.groups));
                self.props.onComeback();
                self.props.onLoadElem(null);
            })
            .catch(function (error) {
                Formulaire.displayErrors(self, error);
            })
            .then(function () { })
        ;
    }
}

export class ClasseList extends Component {
    constructor(props) {
        super(props);

        this.state = {
            classe: null,
            choice: null
        }

        this.handleSelectClasse = this.handleSelectClasse.bind(this);
        this.handleSelectChoice = this.handleSelectChoice.bind(this);
        this.handleChangeSuspended = this.handleChangeSuspended.bind(this);
        this.handleChangeDispensed = this.handleChangeDispensed.bind(this);
        this.handleDelete = this.handleDelete.bind(this);
    }

    handleSelectClasse = (classe) => {
        this.setState((prevState) => {
            return { classe: prevState.classe === classe ? null : classe, choice: null }
        })
    }

    handleSelectChoice = (choice) => {
        this.setState({ choice: choice })
    }

    handleChangeSuspended = (groupId, elv) => {
        runChange(this, this.props.role, Routing.generate('api_level_change_suspended', {'group': groupId}), elv, "POST")
    }

    handleChangeDispensed = (groupId, elv) => {
        runChange(this, this.props.role, Routing.generate('api_level_change_dispensed', {'group': groupId, 'eleve': elv.id}), elv, "POST")
    }

    handleDelete = (groupId, elv) => {
        runChange(this, this.props.role, Routing.generate('api_level_delete_eleve', {'group': groupId}), elv, "DELETE")
    }

    render () {
        const { developer, role, data, onLevelUp, onStay, onEdit, loadElem, onGive, centre, onOpenAside, onFinal } = this.props;
        const { classe, choice } = this.state;

        return <>
            <div>
                <div className="items-table">
                    {data ? <ClasseItem developer={developer} role={role} classes={data} classe={classe} loadElem={loadElem}
                                        onLevelUp={onLevelUp} onStay={onStay} onEdit={onEdit} onGive={onGive} onFinal={onFinal}
                                        centre={centre}
                                        onChangeDispensed={this.handleChangeDispensed}
                                        onChangeSuspended={this.handleChangeSuspended}
                                        onDelete={this.handleDelete}
                                        onSelectChoice={this.handleSelectChoice} choiceActive={choice}
                                        onSelectClasse={this.handleSelectClasse}
                                        onOpenAside={onOpenAside}
                    /> : <LoaderElement />}
                </div>
            </div>
        </>
    }
}