import React, { Component } from "react";

import Swal         from "sweetalert2";
import SwalOptions  from "@commonComponents/functions/swalOptions";

import { Button }   from "@dashboardComponents/Tools/Button";

export class Step extends Component {
    constructor(props) {
        super();

        this.handleCancel = this.handleCancel.bind(this);
    }

    handleCancel = () => {
        let self = this;
        Swal.fire(SwalOptions.options('Etes-vous sûr de vouloir annuler cette réservation ?', 'Les informations ne seront pas sauvegardées.'))
            .then((result) => {
                if (result.isConfirmed) {
                    self.props.onCancelBooking()
                }
            })
    }

    render () {
        const { context, content, txtBack="Retour", txtNext="Suivant", onClickBack=this.handleCancel, onClickNext } = this.props

        let items = [
            {'active': false, 'context': 'profil', 'label': 'Profil'},
            {'active': false, 'context': 'responsable', 'label': 'Référent'},
            {'active': false, 'context': 'eleves', 'label': 'Eleves'},
            {'active': false, 'context': 'review', 'label': 'Vérification'},
            {'active': false, 'context': 'ticket', 'label': 'Réservation'},
        ];

        let active = false;
        let itemsMod = [];
        items.forEach(elem => {
            if(!active){
                elem.active = true;
            }

            if(elem.context === context){
                active = true;
            }

            itemsMod.push(elem);
        })

        return <div className="step">
            {context !== "ticket" && <div className="step-cancel">
                <Button type="danger" onClick={this.handleCancel}>Annuler la réservation</Button>
            </div>}

            <div className="step-path">
                {itemsMod.map((elem, index) => {
                    return <div className={`item ${elem.active}`} key={elem.context}>
                        <span className="label">{elem.label}</span>
                        <span className="number">{index + 1}</span>
                    </div>
                })}
            </div>

            <div className="step-content">
                {content}
            </div>

            {context !== "ticket" && <div className="step-actions">
                <Button onClick={onClickBack} type="default">{txtBack}</Button>
                <Button onClick={onClickNext}>{txtNext}</Button>
            </div>}
        </div>
    }
}