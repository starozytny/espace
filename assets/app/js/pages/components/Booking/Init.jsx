import React from "react";

import Routing           from "@publicFolder/bundles/fosjsrouting/js/router.min";

import { Alert }  from "@dashboardComponents/Tools/Alert";
import { Button } from "@dashboardComponents/Tools/Button";

export function Init ({ isClose, onChangeContext }) {

    let alertContent = <div>
        <p>
            Le jour de l'inscription, il vous sera demandé de fournir les documents suivants :
        </p>
        <ListDocuments />
    </div>

    return <>
        <div className="launch">
            <div className="explain">
                <p>
                    La réservation d'un ticket permet d'<b><u>obtenir 1 ticket</u> par famille</b>. <br/>
                    <br/>
                    Votre ticket et horaire de rendez-vous vous seront envoyés par email. <br/>
                    <u>Veuillez à vérifier vos spams/courriers indésirables.</u>
                    <br/><br/>
                    <b>Important :</b> Pour des raisons sanitaires, nous vous invitons à limiter le nombre
                    d'accompagnants
                    et tout particulièrement les petits enfants. Le port du masque est obligatoire
                    <br/> <br/>
                    Pour toutes informations concernant le déroulement de cette journée :
                    04 91 39 28 28
                </p>
                <div className="link-to-myBooking">
                    <Button type="default" outline={true} element="a" onClick={Routing.generate('app_my_booking')}>Ma réservation</Button>
                </div>
            </div>
            <div className="starter">
                <Alert type="warning" content={alertContent} />

                {isClose ? <Alert type="danger" title="Clôture des réservations" >
                    La réservation de tickets n'est pas encore ouverte ou il n'y a plus de tickets disponibles pour le moment.
                </Alert> : <Button onClick={onChangeContext}>Commencer la réservation</Button>}
            </div>
        </div>
    </>
}

export function ListDocuments () {
    return (<ul>
        <li>Photocopie du dernier avis d'imposition (<u>2020</u> sur les revenus 2019 - toutes les pages)</li>
        <li>Photocopie Justificatif de domicile (moins de 3 mois)</li>
        <li>Photocopie de la carte étudiante pour les étudiants de moins de 26 ans</li>
        <li>Règlement ce jour là par <u>chèque ou espèces</u> (CB non acceptée)</li>
    </ul>)
}