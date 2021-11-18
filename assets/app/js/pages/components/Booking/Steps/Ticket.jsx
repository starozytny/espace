import React, { Component } from "react";

import Routing           from "@publicFolder/bundles/fosjsrouting/js/router.min";

import { Alert }         from "@dashboardComponents/Tools/Alert";
import { Step }          from "../Step"
import { ListDocuments } from "../Init";

export class Ticket extends Component {

    render () {
        const { responsable } = this.props

        let content = <div className="review-content">
            <section>
                <div className="step-title">Mon ticket</div>
                <TicketContent responsable={responsable}/>
            </section>
        </div>

        return <div>
            <Step {...this.props} content={content}/>
        </div>
    }
}

export function TicketContent ({ responsable }) {
    return (<>
        <div className="liste-ticket">
            <p>TICKET : <span className="huge-ticket">{responsable.ticket}</span></p>

            {responsable.status === 1 ? <>
                <div className="barcode">
                    <img src={ "data:image/png;base64," + responsable.barcode }  alt="barcode du ticket de réservation"/>
                </div>

                <div className="rdv">
                    Pour le {responsable.day.dayLongString} à {responsable.slot.timetableString}
                </div>

                <a className="btn btn-primary" target="_blank" href={Routing.generate('api_booking_ticket_print', {'responsable': responsable.id, 'ticket': responsable.ticket})}>
                    <span>Imprimer mon ticket</span>
                </a>
            </> : <>
                <Alert type="warning">
                    Veuillez <u>conserver votre numéro de ticket</u> afin d'accéder à votre réservation ! La date et horaire de passage vous seront communiqués par mail.<br/>
                    La réception du mail peut prendre plusieurs minutes en raison de la forte affluence. <br/>
                    TICKET : <span className="huge-ticket">{responsable.ticket}</span>
                </Alert>
                {/*<Alert type="default">*/}
                {/*    Votre date et horaire de passage vous seront communiqués par mail, la réception du mail*/}
                {/*    peut prendre plusieurs minutes avant d'être expédié en raison de la forte affluence. <br/>*/}
                {/*    Pensez à vérifier vos spams/courriers indésirables.*/}
                {/*</Alert>*/}
            </>}

            <div className="reception">
                Pensez à vérifier vos spams/courriers indésirables. <br/>
                La réception du mail peut prendre plusieurs minutes.
            </div>
        </div>
        <div className="rappel">
            <p><b>Rappel : </b> le jour de l'inscription, il vous sera demandé de fournir les documents suivants : :</p>
            <ListDocuments />
        </div>
    </>)
}