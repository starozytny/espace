import React, { Component } from "react";

import axios             from "axios";
import Routing           from "@publicFolder/bundles/fosjsrouting/js/router.min";

import { LoaderElement } from "@dashboardComponents/Layout/Loader";
import { Alert }         from "@dashboardComponents/Tools/Alert";
import Formulaire        from "@dashboardComponents/functions/Formulaire";
import Helper            from "./functions/helper";

import { EditBooking }   from "./Edit/EditBooking";
import { SearchBooking } from "./Edit/SearchBooking";

export class MyBooking extends Component {
    constructor(props) {
        super(props);

        this.state = {
            user: props.user,
            loadPageError: false,
            loadData: true,
            context: "edit",
            haveBooking: false
        }

        this.handleChangeContext = this.handleChangeContext.bind(this);

        this.handleUpdateUser = this.handleUpdateUser.bind(this);

        this.handleAddEleve = this.handleAddEleve.bind(this);
        this.handleDeleteEleve = this.handleDeleteEleve.bind(this);
    }

    componentDidMount = () => {
        const { user } = this.props;

        if(user){
            const self = this;
            Formulaire.loader(true);
            axios({ method: "GET", url: Routing.generate('api_booking_responsable_bo_user', {'id': user}), data: {} })
                .then(function (response) {
                    let data = response.data;

                    if(data){
                        self.handleUpdateUser(data);
                    }
                })
                .catch(function (error) {
                    Formulaire.displayErrors(self, error, "Une erreur est survenue, veuillez contacter le support.")
                })
                .then(() => {
                    Formulaire.loader(false);
                    self.setState({ loadData: false })
                })
            ;
        }
    }

    handleChangeContext = (context) => {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera

        this.setState({ context: context });
    }

    handleUpdateUser = (user) =>{ this.setState({ responsable: user, eleves: user.eleves, haveBooking: true, loadData: false }) }

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

    render () {
        const { loadPageError, loadData, context, haveBooking } = this.state;

        let content = null;
        switch (context){
            default:
                content = loadData ? <LoaderElement /> : <EditBooking {...this.state} onAddEleve={this.handleAddEleve} onDeleteEleve={this.handleDeleteEleve}/>
                break;
        }

        if(loadPageError && !loadData){
            content = <Alert type="danger" title="Impossible de récupérer la réservation">
                Veuillez réessayer ultérieurement. Si le problème persiste, veuillez contacter le support.
            </Alert>
        }

        return <div>
            {haveBooking ? content : <SearchBooking onUpdateUser={this.handleUpdateUser}/>}
        </div>
    }
}