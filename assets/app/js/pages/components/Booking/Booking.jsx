import React, { Component } from "react";

import axios             from "axios";
import Routing           from "@publicFolder/bundles/fosjsrouting/js/router.min";

import { LoaderElement } from "@dashboardComponents/Layout/Loader";
import { Alert }         from "@dashboardComponents/Tools/Alert";
import Formulaire        from "@dashboardComponents/functions/Formulaire";
import Sanitaze          from "@commonComponents/functions/sanitaze";

import { Init }          from "./Init";
import { Profil }        from "./Steps/Profil";
import { Responsable }   from "./Steps/Responsable";
import { Eleves }        from "./Steps/Eleves";
import { Review }        from "./Steps/Review";
import { Ticket }        from "./Steps/Ticket";
import Helper            from "./functions/helper";

export class Booking extends Component {
    constructor(props) {
        super(props);

        this.state = {
            user: props.user,
            loadPageError: false,
            loadData: true,
            context: "init",
            day: null,
            dayType: 0,
            isClose: false,
            inMobile: false,
            profil: props.user ? 0 : null,
            type: null,
            responsable: null,
            responsableUser: null,
            eleves: [],
            elevesUser: [],
            arrayPostalCode: [],
        }

        this.handleChangeContext = this.handleChangeContext.bind(this);
        this.handleConfirmeExit = this.handleConfirmeExit.bind(this);
        this.handleCancelBooking = this.handleCancelBooking.bind(this);
        this.handleUnload = this.handleUnload.bind(this);

        this.handleSelectProfil = this.handleSelectProfil.bind(this);
        this.handleSelectType = this.handleSelectType.bind(this);

        this.handleUpdateResponsable = this.handleUpdateResponsable.bind(this);
        this.handleUpdateUser = this.handleUpdateUser.bind(this);

        this.handleAddEleve = this.handleAddEleve.bind(this);
        this.handleDeleteEleve = this.handleDeleteEleve.bind(this);
    }

    componentDidMount = () => {
        const { donnees } = this.props;
        const { arrayPostalCode, profil } = this.state;

        let data = JSON.parse(donnees);
        if(data){
            let x = window.matchMedia("(max-width: 768px)")
            this.setState({ day: parseInt(data.id), inMobile: x.matches, dayType: parseInt(data.type), profil: parseInt(data.type) === 1 ? 0 : profil, isClose: false, loadPageError: false, loadData: false });

            if(arrayPostalCode.length === 0){
                Sanitaze.getPostalCodes(this); // fill arrayPostalCode
            }
        }else{
            this.setState({ isClose: true, loadPageError: false, loadData: false });
        }
    }

    handleChangeContext = (context) => {
        const { responsable, eleves } = this.state;

        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera

        let nResponsable = responsable;
        let nEleves = eleves;

        if(context === "profil"){
            nResponsable = null;
            nEleves = [];
        }

        if(context === "responsable"){
            window.addEventListener("beforeunload", this.handleConfirmeExit);
            window.addEventListener("pagehide", this.handleUnload);
            window.addEventListener("unload", this.handleUnload);
            nEleves = [];
        }

        if(context === "ticket" || context === "profil"){
            window.removeEventListener("beforeunload", this.handleConfirmeExit);
            window.removeEventListener("pagehide", this.handleUnload);
            window.removeEventListener("unload", this.handleUnload);
        }

        this.setState({ context: context, responsable: nResponsable, eleves: nEleves });
    }

    handleConfirmeExit (e) {
        e.preventDefault();
        e.returnValue = "En quittant cette page, les modifications apportées ne seront pas sauvegardées.";
    }

    handleSelectProfil = (profil) => { this.setState({ profil }) }
    handleSelectType = (type) => { this.setState({ type }) }

    handleUpdateResponsable = (responsable, fromExiste = false) => {
        if(fromExiste){
            responsable.id = this.state.responsable.id;
            this.setState({ responsable })
        }else{
            this.setState({ responsable })
        }
    }

    handleUpdateUser = (user) =>{ this.setState({ responsableUser: user ? user : null, elevesUser: user ? user.eleves : null }) }

    handleAddEleve = (data) => {
        this.setState((prevState) => {
            let newEleves = Helper.newArrayEleves(prevState, data);
            return { eleves: newEleves }
        })
    }

    handleDeleteEleve = (eleve) => {
        this.setState((prevState) => {
            let newEleves = prevState.eleves.filter(el => el.id !== eleve.id )
            return { eleves: newEleves }
        })
    }

    handleUnload = () => {
        const { responsable } = this.state;

        if(navigator.sendBeacon){
            let url = Routing.generate('api_booking_responsable_delete_token');
            let fd = new FormData();
            fd.append('token', responsable.token);
            navigator.sendBeacon(url, fd);
        }else{
            let xhr = new XMLHttpRequest();
            let url = Routing.generate('api_booking_responsable_delete', { 'token' : responsable.token });
            xhr.open('DELETE', url, false);
        }
    }

    handleCancelBooking = () => {
        const { responsable } = this.state;

        if(responsable){
            const self = this;
            Formulaire.loader(true);
            axios.delete(Routing.generate('api_booking_responsable_delete', {'token': responsable.token}), {})
                .then(function (response) {
                    window.removeEventListener("beforeunload", self.handleConfirmeExit);
                    window.removeEventListener("pagehide", self.handleUnload);
                    window.removeEventListener("unload", self.handleUnload);
                    // self.handleChangeContext("init");
                    location.reload()
                })
                .catch(function (error) {
                    Formulaire.displayErrors(self, error, "Une erreur est survenue, veuillez rafraichir la page.")
                })
            ;
        }else{
            this.handleChangeContext("init");
        }
    }

    render () {
        const { loadPageError, loadData, context, isClose } = this.state;

        let content = null;
        switch (context){
            case "profil":
                content = <Profil {...this.state} onChangeContext={this.handleChangeContext} onCancelBooking={this.handleCancelBooking}
                                  onSelectProfil={this.handleSelectProfil} onSelectType={this.handleSelectType}
                                  onUpdateResponsable={this.handleUpdateResponsable} onUpdateUser={this.handleUpdateUser}/>
                break;
            case "responsable":
                content = <Responsable {...this.state} onChangeContext={this.handleChangeContext} onCancelBooking={this.handleCancelBooking}
                                       onUpdateResponsable={this.handleUpdateResponsable} onUpdateUser={this.handleUpdateUser}/>
                break;
            case "eleves":
                content = <Eleves {...this.state} onChangeContext={this.handleChangeContext} onCancelBooking={this.handleCancelBooking}
                                   onAddEleve={this.handleAddEleve} onDeleteEleve={this.handleDeleteEleve}/>
                break;
            case "review":
                content = <Review {...this.state} onChangeContext={this.handleChangeContext} onCancelBooking={this.handleCancelBooking}
                                  onUpdateResponsable={this.handleUpdateResponsable}/>
                break;
            case "ticket":
                content = <Ticket {...this.state} />
                break;
            default:
                content = loadData ? <LoaderElement /> : <Init isClose={isClose} onChangeContext={() => this.handleChangeContext("profil")}/>
                break;
        }

        if(loadPageError && !loadData){
            content = <Alert type="danger" title="Réservation impossible">
                Veuillez réessayer ultérieurement. Si le problème persiste, veuillez contacter le support.
            </Alert>
        }

        return <div>
            {content}
        </div>
    }
}