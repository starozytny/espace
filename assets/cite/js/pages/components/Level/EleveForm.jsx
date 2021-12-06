import React, { Component } from 'react';

import axios                    from "axios";
import Routing                  from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Alert }                from "@dashboardComponents/Tools/Alert";
import { Button }               from "@dashboardComponents/Tools/Button";
import { FormLayout }           from "@dashboardComponents/Layout/Elements";
import { SelectReactSelectize } from "@dashboardComponents/Tools/Fields";

import Validateur               from "@commonComponents/functions/validateur";
import Sort                     from "@commonComponents/functions/sort";
import Formulaire               from "@dashboardComponents/functions/Formulaire";

export function EleveFormulaire ({ classe, tmCours, elevesGrp, onUpdateClasse, onComeback, onLoadElem, onCloseAside })
{
    let url = Routing.generate('api_level_add_eleve', {'id': classe.id});
    let msg = "Félicitation ! Vous avez ajouté un élève à cette classe !"

    let form = <EleveForm
        url={url}
        tmCours={tmCours}
        classe={classe}
        elevesGrp={elevesGrp}
        messageSuccess={msg}
        onUpdateClasse={onUpdateClasse}
        onComeback={onComeback}
        onLoadElem={onLoadElem}
        onCloseAside={onCloseAside}
    />

    return <FormLayout form={form} />
}

export class EleveForm extends Component {
    constructor(props) {
        super(props);

        this.state = {
            tmCours: props.tmCours,
            errors: [],
            success: false,
            eleves: null,
            eleve: ""
        }

        this.handleChangeSelect = this.handleChangeSelect.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    componentDidMount() {
        const { eleves, elevesGrp } = this.state;

        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera

        if(eleves === null){
            Formulaire.loader(true);
            const self = this;
            axios({ method: "GET", url: Routing.generate('api_eleves_data') })
                .then(function (response) {
                    let data = response.data;
                    self.setState({ eleves: data });
                })
                .catch(function (error) {
                    Formulaire.displayErrors(self, error);
                })
                .then(() => {
                    Formulaire.loader(false);
                })
            ;
        }
    }

    handleChangeSelect = (e) => { this.setState({ eleve: e !== undefined ? e.value : "" });}

    handleSubmit = (e) => {
        e.preventDefault();

        const { url, tmCours } = this.props;
        const { eleve } = this.state;

        this.setState({ success: false})

        let method = "POST";
        let paramsToValidate = [
            {type: "text", id: 'eleve', value: eleve},
            {type: "text", id: 'eleve', value: tmCours}
        ];

        // validate global
        let validate = Validateur.validateur(paramsToValidate)

        // check validate success
        if(!validate.code){
            this.setState({ errors: validate.errors });
        }else{
            Formulaire.loader(true);
            let self = this;
            axios({ method: method, url: url, data: {tmCours: tmCours, eleve: eleve} })
                .then(function (response) {
                    let resp = response.data;
                    self.setState({ eleve: "" })
                    self.props.onUpdateClasse(JSON.parse(resp.classe), null, resp.tmGroups, JSON.parse(resp.groups));
                    self.props.onComeback();
                    self.props.onLoadElem(null);
                    self.props.onCloseAside();
                })
                .catch(function (error) {
                    Formulaire.displayErrors(self, error);
                })
                .then(() => {
                    Formulaire.loader(false);
                })
            ;
        }
    }

    render () {
        const { elevesGrp } = this.props;
        const { errors, success, eleves, eleve } = this.state;

        let selectEleves = [];
        if(eleves !== null){
            eleves.sort(Sort.compareLastname);
            eleves.forEach(el => {
                let addElem = true;
                elevesGrp.forEach(elem => {
                    if(el.id === elem.id){
                        addElem = false;
                    }
                })

                if(addElem){
                    selectEleves.push({ value: el.id, label: el.lastname + " " + el.firstname, identifiant: "eleve-" + el.id })
                }
            })
        }

        return <>
            <form onSubmit={this.handleSubmit}>

                {success !== false && <Alert type="info">{success}</Alert>}

                <div className="line">
                    <SelectReactSelectize items={selectEleves} identifiant="eleves" placeholder={"Sélectionner l'élève"} valeur={eleve} errors={errors} onChange={this.handleChangeSelect}>
                        Choisir l'élève à ajouter
                    </SelectReactSelectize>
                </div>

                <div className="line">
                    <div className="form-button">
                        <Button isSubmit={true}>Ajouter</Button>
                    </div>
                </div>
            </form>
        </>
    }
}