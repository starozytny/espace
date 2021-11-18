import React, { Component } from "react";

import axios          from "axios";
import Routing        from "@publicFolder/bundles/fosjsrouting/js/router.min";

import Sanitize       from "@commonComponents/functions/sanitaze";
import Formulaire     from "@dashboardComponents/functions/Formulaire";

import { Step }       from "../Step"
import { Card }       from "./Card";

export class Review extends Component {
    constructor(props) {
        super();

        this.handleBack = this.handleBack.bind(this);
        this.handleNext = this.handleNext.bind(this);
    }

    handleBack = () => { this.props.onChangeContext("eleves") }

    handleNext = (e) => {
        const { day, responsable, eleves } = this.props;
        e.preventDefault();

        Formulaire.loader(true);
        const self = this;
        axios({ method: "POST", url: Routing.generate('api_booking_ticket_create', {'day': day}), data: {responsable: responsable, eleves: eleves} })
            .then(function (response) {
                let data = response.data;
                self.props.onUpdateResponsable(data)
                self.props.onChangeContext("ticket");
            })
            .catch(function (error) {
                Formulaire.displayErrors(self, error)
            })
            .then(() => {
                Formulaire.loader(false);
            })
        ;
    }

    render () {
        const { responsable, eleves, onCancelBooking } = this.props;

        let content = <div className="review-content">
            <section>
                <div className="step-title">Vérification</div>
                <div className="step-title-infos">
                    <p>
                        Pour obtenir votre ticket et connaitre la date de votre rendez-vous, veuillez vérifier les informations
                        que vous avez saisie et puis cliquer sur <b>OBTENIR MON TICKET</b>.
                    </p>
                </div>
                <div className="liste-review">
                    <div className="liste-review-responsable">
                        <div className="liste-review-title">Référent</div>
                        <div className="responsable">
                            <div className="eleve-infos">
                                <div className="name">{responsable.civility === 0 ? "Mr" : "Mme"}. {responsable.firstname} {responsable.lastname}</div>
                                <div className="email">{responsable.email}</div>
                                <div className="phone">{Sanitize.toFormatPhone(responsable.phone1)}</div>
                            </div>
                        </div>
                    </div>
                    <div className="liste-review-eleves">
                        <div className="liste-review-title">Les élèves concernés</div>
                        {eleves && eleves.map((elem, index) => {
                            return <div key={index} className="eleve">
                                <Card elem={elem} civility={elem.civility}/>
                            </div>
                        })}
                    </div>
                </div>
            </section>
        </div>

        return <div>
            <Step {...this.props} content={content} onCancelBooking={onCancelBooking}
                  onClickBack={this.handleBack} onClickNext={this.handleNext} txtNext="Valider ma réservation"/>
        </div>
    }
}