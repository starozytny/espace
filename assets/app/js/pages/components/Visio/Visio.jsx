import React, { Component } from "react";

import axios      from "axios";
import Routing    from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Input }  from "@dashboardComponents/Tools/Fields";
import { Button } from "@dashboardComponents/Tools/Button";
import Formulaire from "@dashboardComponents/functions/Formulaire";
import Validator  from "@commonComponents/functions/validateur";

export class BBB extends Component {
    constructor(props) {
        super();

        this.state = {
            errors: [],
            fullname: ""
        }

        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleChange = (e) => { this.setState({ [e.currentTarget.name]: e.currentTarget.value }) }

    handleSubmit = (e) => {
        e.preventDefault();

        const { fullname } = this.state;
        const { teacher, nameRoom, isCustom, lesson } = this.props;

        let validate = Validator.validateur([
            {type: "text", id: 'fullname', value: fullname},
        ])

        if(!validate.code){
            this.setState({ errors: validate.errors });
        }else{
            const self = this;
            Formulaire.loader(true);

            let url;
            if(this.props.context === "recent"){
                url = Routing.generate('app_join_bbb', {'teacher': teacher, 'nameRoom': nameRoom, 'isCustom': isCustom, 'lesson': lesson ? lesson : 0})
            }else{
                url = Routing.generate('app_join_bbb_old', {'nameRoom': nameRoom})
            }

            axios({ method: "POST", url: url, data: self.state })
                .then(function (response) {
                    let data = response.data;
                    window.open(data)
                    self.setState({ errors: []});
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

    render() {
        const { fullname, errors } = this.state;

        return <div className="main-content">
            <form onSubmit={this.handleSubmit}>
                <div className="line">
                    <Input identifiant="fullname" valeur={fullname} errors={errors} onChange={this.handleChange}>Votre nom</Input>
                </div>

                <div className="line">
                    <div className="form-button">
                        <Button isSubmit={true}>Rejoindre la salle</Button>
                    </div>
                </div>
            </form>
        </div>
    }
}