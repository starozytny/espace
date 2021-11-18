import React, { Component } from "react";

import axios                from "axios";
import toastr               from "toastr";
import Routing              from "@publicFolder/bundles/fosjsrouting/js/router.min";

import { Button }           from "@dashboardComponents/Tools/Button";
import { DatePick }         from "@dashboardComponents/Tools/DatePicker";
import { Input, Radiobox }  from "@dashboardComponents/Tools/Fields";

import Validateur           from "@commonComponents/functions/validateur";
import Formulaire           from "@dashboardComponents/functions/Formulaire";
import Sanitaze             from "@commonComponents/functions/sanitaze";

export class EleveForm extends Component {
    constructor(props) {
        super();

        this.state = {
            errors: [],
            id: null,
            isAncien: false,
            referral: null,
            isResponsable: false,
            responsable: props.responsable.id,
            civility: "",
            lastname: "",
            firstname: "",
            email: "",
            emailResponsable: props.responsable.email,
            phoneMobile: "",
            birthday: null,
        }

        this.handleUpdateData = this.handleUpdateData.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.handleChangeDateBirthday = this.handleChangeDateBirthday.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleAddEleve = this.handleAddEleve.bind(this);
        this.handleUpdateEleve = this.handleUpdateEleve.bind(this);
    }

    handleUpdateData = (element) => {
        let civility = "";
        if(element){
            if(element.civility === "Mr"){
                civility = 0;
            }else if(element.civility === "Mme"){
                civility = 1;
            }
        }

        this.setState( {
            context: element ? "update" : "create",
            id: element ? element.id : null,
            civility: civility,
            lastname: element ? element.lastname : "",
            firstname: element ? element.firstname : "",
            email: element ? (element.email ? element.email : "") : "",
            phoneMobile: element ? (element.phoneMobile ? element.phoneMobile : "") : "",
            birthday: element ? new Date(element.birthday) : null,
            referral: element ? element.referral : null,
            isAncien: element ? element.isAncien : false,
        })
    }

    handleChange = (e) => { this.setState({ [e.currentTarget.name]: e.currentTarget.value }) }
    handleChangeDateBirthday= (e) => { this.setState({ birthday: e }) }

    handleCreateOrUpdateEleve = (method, url, element) => {
        Formulaire.loader(true);
        let self = this;
        axios({ method: method, url: url, data: element })
            .then(function (response) {
                let data = response.data;
                self.props.onAddEleve(data);
            })
            .catch(function (error) {
                Formulaire.displayErrors(self, error);
            })
            .then(() => {
                Formulaire.loader(false);
            })
        ;
    }

    handleAddEleve = (element) => {
        this.handleCreateOrUpdateEleve("POST", Routing.generate('api_booking_eleves_create'), element);
    }

    handleUpdateEleve = (id, element) => {
        this.handleCreateOrUpdateEleve("PUT", Routing.generate('api_booking_eleves_update', {'id': id}), element);
    }

    handleSubmit = (e) => {
        e.preventDefault();

        const { context, id, lastname, firstname, birthday, civility } = this.state;

        let validate = Validateur.validateur([
            {type: "text", id: 'civility', value: civility},
            {type: "text", id: 'lastname', value: lastname},
            {type: "text", id: 'firstname', value: firstname},
            {type: "date", id: 'birthday', value: birthday},
        ])

        if(!validate.code) {
            this.setState({errors: validate.errors});
            toastr.error("Veuillez vérifier que tous les champs obligatoires soient renseignés")
        }else{
            if(context === "create"){
                this.handleAddEleve(this.state)
            }else{
                this.handleUpdateEleve(id, this.state)
            }
        }
    }

    render () {
        const { errors, lastname, firstname, email, phoneMobile, birthday, civility, referral } = this.state;

        let civilityItems = [
            { 'value': 0, 'label': 'Mr', 'identifiant': 'monsieur' },
            { 'value': 1, 'label': 'Mme', 'identifiant': 'madame' },
        ]

        return <form onSubmit={this.handleSubmit}>
            <div className="line">
                {referral ? <div className="form-group">
                        <label htmlFor="birthday">Civilité</label>
                        <div>{civility === 0 ? "Mr" : "Mme"}</div><input type="hidden" name="civility" id="civility" value={civility} />
                </div> :
                <Radiobox items={civilityItems} identifiant="civility" valeur={civility} errors={errors} onChange={this.handleChange}>Civilité</Radiobox>}
            </div>
            <div className="line line-2">
                {referral ? <div className="form-group">
                        <label htmlFor="birthday">Prénom</label>
                        <div>{firstname}</div><input type="hidden" name="firstname" id="firstname" value={firstname} />
                </div> :
                    <Input identifiant="firstname" valeur={firstname} errors={errors} onChange={this.handleChange}>Prénom</Input>}

                {referral ? <div className="form-group">
                        <label htmlFor="birthday">Nom</label>
                        <div>{lastname}</div><input type="hidden" name="lastname" id="lastname" value={lastname} />
                </div> :
                    <Input identifiant="lastname" valeur={lastname} errors={errors} onChange={this.handleChange}>Nom</Input>}
            </div>
            <div className="line">
                {referral ? <div className="form-group">
                        <label htmlFor="birthday">Date de naissance</label>
                        <div>{Sanitaze.toFormatDate(birthday)}</div><input type="hidden" name="birthday" id="birthday" value={birthday} />
                </div> :
                    <DatePick identifiant="birthday" valeur={birthday} maxDate={new Date()} errors={errors} onChange={this.handleChangeDateBirthday}>Date de naissance</DatePick>}
            </div>
            <div className="line line-2">
                <Input type="email" identifiant="email" valeur={email} errors={errors} placeholder="(Facultatif)" onChange={this.handleChange}>Adresse e-mail</Input>
                <Input type="number" identifiant="phoneMobile" valeur={phoneMobile} errors={errors} placeholder="(Facultatif)" onChange={this.handleChange}>Téléphone mobile</Input>
            </div>

            <div className="line">
                <div className="alert alert-default">
                    <span className="icon-information" />
                    <p>
                        Les données à caractère personnel collectées dans ce formulaire d'inscription, par la
                        Cité de la Musique de Marseille sont enregistrées dans un fichier informatisé permettant
                        la gestion des dossiers. Ce fichier est hébergé en France par OVH , la Société Logilink
                        assure la maintenance de notre serveur et la sécurité de celui-ci. <br/>
                        Par cette inscription, vous consentez à nous transmettre vos données pour vous offrir ce service.
                        <br/>
                        Plus d'informations sur le traitement de vos données dans notre <a href={Routing.generate('app_politique')}>politique de confidentialité</a>.
                    </p>
                </div>
            </div>

            <div className="form-button">
                <Button isSubmit={true}>Valider la saisie</Button>
            </div>
        </form>
    }
}