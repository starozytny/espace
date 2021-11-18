import React, { Component } from "react";

import axios        from "axios";
import toastr       from "toastr";
import Routing      from "@publicFolder/bundles/fosjsrouting/js/router.min";

import Formulaire           from "@dashboardComponents/functions/Formulaire";
import { LoaderElement }    from "@dashboardComponents/Layout/Loader";
import { Alert }            from "@dashboardComponents/Tools/Alert";
import { Button }           from "@dashboardComponents/Tools/Button";

import { Step }     from "../Step";

export class Profil extends Component {
    constructor(props) {
        super();

        this.state = {
            errors: null,
            loadUserError: null,
            loadUser: false
        }

        this.handleNext = this.handleNext.bind(this);
    }

    componentDidMount = () => {
        const { user, responsableUser } = this.props;

        if(user && responsableUser === null){
            const self = this;
            Formulaire.loader(true);
            axios({ method: "GET", url: Routing.generate('api_booking_responsable_user', {'id': self.props.user}), data: {} })
                .then(function (response) {
                    let data = response.data;

                    if(data.code !== undefined && data.code === 0){
                        self.setState({ loadUserError: data.message });
                    }else{
                        self.props.onUpdateUser(data);
                    }
                })
                .catch(function (error) {
                    if(error.response.data.message){
                        self.setState({ loadUserError: error.response.data.message });
                    }else{
                        toastr.error("Veuillez vérifier les informations transmises.");
                    }
                })
                .then(() => {
                    Formulaire.loader(false);
                    self.setState({ loadUser: true })
                })
            ;
        }else{
            this.setState({ loadUser: true })
        }
    }

    handleNext = () => {
        const { profil, type, responsable } = this.props;

        if(profil === null || type === null){
            let errors = "Veuillez sélectionner votre profil et qui vous souhaitez inscrire pour continuer la réservation du ticket."
            this.setState({ errors });
            toastr.error(errors)
        }else{
            if(responsable === null){
                let self = this;
                Formulaire.loader(true);
                axios({ method: "POST", url: Routing.generate('api_booking_responsable_start'), data: self.props })
                    .then(function (response) {
                        let data = response.data;
                        self.setState({ errors: null });
                        self.props.onUpdateResponsable(data);
                        self.props.onChangeContext("responsable");
                    })
                    .catch(function (error) {
                        Formulaire.displayErrors(self, error)
                    })
                    .then(() => {
                        Formulaire.loader(false);
                    })
                ;
            }else{
                this.props.onChangeContext("responsable");
            }
        }
    }

    render () {
        const { dayType, user, profil, type, onSelectProfil, onSelectType, onCancelBooking, onChangeContext } = this.props;
        const { errors, loadUser, loadUserError } = this.state;

        let profils = [
            { 'id': 0, 'label': 'Adhérent ou Ancien adhérent' },
            { 'id': 2, 'label': 'Nouveau' }
        ]

        let types = [
            { 'id': 0, 'label': "Moi" },
            { 'id': 1, 'label': "Moi et d'autres personnes" },
            { 'id': 2, 'label': "Inscrire d'autres personnes" }
        ]

        let content = <div className="profil-content">
            <section>
                <div className="step-title">Cliquez sur votre profil</div>
                <div className="choice-profil">
                    {(user || dayType === 1) ? <div className="item true">Adhérent ou Ancien adhérent</div> : profils.map(elem => {
                        return <div className={`item ${profil === elem.id}`} onClick={() => onSelectProfil(elem.id)} key={elem.id}>
                            <span>{elem.label}</span>
                        </div>
                    })}
                </div>
            </section>
            <section>
                <div className="step-title">Qui souhaitez-vous inscrire ?</div>
                <div className="choice-profil">
                    {types.map(elem => {
                        return <div className={`item ${type === elem.id}`} onClick={() => onSelectType(elem.id)} key={elem.id}>
                            <span>{elem.label}</span>
                        </div>
                    })}
                </div>
            </section>
            {errors &&  <div className="form-group-error">
                <div className="alert alert-danger">
                    <span className='icon-warning'/>
                    <p>{errors}</p>
                </div>
            </div>}
        </div>

        return <div>
            {!loadUser ? <LoaderElement />
                : (loadUserError ? <>
                    <Alert type="danger">{loadUserError}</Alert>
                    <div className="already-book">
                        <Button onClick={() => onChangeContext("init")} type="default" outline={true}>Retour à la page précédente</Button>
                        <Button element="a" onClick={Routing.generate('app_my_booking')} type="primary">Modifier ma réservation</Button>
                    </div>
                </> : <Step {...this.props} content={content} onClickNext={this.handleNext} onCancelBooking={onCancelBooking}/>)
            }
        </div>
    }
}