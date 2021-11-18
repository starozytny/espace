import React, { Component } from "react";

import axios            from "axios";
import toastr           from "toastr";
import Routing          from "@publicFolder/bundles/fosjsrouting/js/router.min";

import Formulaire       from "@dashboardComponents/functions/Formulaire";
import Validateur       from "@commonComponents/functions/validateur";

import { Button }       from "@dashboardComponents/Tools/Button";
import { Alert }        from "@dashboardComponents/Tools/Alert";
import { Input }        from "@dashboardComponents/Tools/Fields";


export class SearchBooking extends Component {
    constructor(props) {
        super();

        this.state = {
            reference: "",
            lastname: "",
            email: "",
            errors: []
        }

        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleChange = (e) => { this.setState({ [e.currentTarget.name]: e.currentTarget.value }) }

    handleSubmit = (e) => {
        e.preventDefault();

        const { reference, lastname, email } = this.state;

        let validate = Validateur.validateur([
            {type: "text", id: 'reference', value: reference},
            {type: "text", id: 'lastname', value: lastname},
            {type: "text", id: 'email', value: email},
        ])

        if(!validate.code) {
            this.setState({errors: validate.errors});
            toastr.error("Veuillez vérifier que tous les champs soient renseignés")
        }else{
            const self = this;
            Formulaire.loader(true);
            axios.post(Routing.generate('api_booking_ticket_get'), self.state)
                .then(function (response) {
                    self.props.onUpdateUser(response.data)
                })
                .catch(function (error) {
                    Formulaire.displayErrors(self, error, "Une erreur est survenue, veuillez rafraichir la page.")
                })
                .then(() => {
                    Formulaire.loader(false);
                })
        }
    }

    render () {
        const { reference, lastname, email, errors } = this.state;

        return <div className="search-booking">
            <div className="search-booking-infos">
                <h2>Accédez à votre réservation/ticket</h2>
                <p>
                    Consultez, modifiez votre réservation et imprimez votre ticket. <br/><br/>
                    Votre numéro de ticket se trouve dans le mail que vous avez reçu lors de votre réservation. <br/>
                    Le mail peut prendre plusieurs minutes avant d'être expédié en raison de la forte affluence et veuillez consultez vos spams/courriers indésirables.
                </p>
            </div>

            <form onSubmit={this.handleSubmit}>
                <div className="line">
                    <Input identifiant="reference" valeur={reference} errors={errors} onChange={this.handleChange}>Numéro de ticket</Input>
                </div>
                <div className="line line-2">
                    <Input identifiant="lastname" valeur={lastname} errors={errors} onChange={this.handleChange}>Nom de famille</Input>
                    <Input identifiant="email" valeur={email} errors={errors} onChange={this.handleChange} type="email">E-mail utilisé pour le ticket</Input>
                </div>
                <div className="line">
                    <Button isSubmit={true}>Consulter ma réservation</Button>
                </div>
            </form>

            <Alert>Si vous avez réaliser la réservation avec votre compte, gagnez du temps ! Connectez-vous pour accéder directement à votre réservation. </Alert>
            <div className="line">
                <Button type="default" element="a" onClick={Routing.generate('app_login')}>Me connecter à mon espace</Button>
            </div>
        </div>
    }
}