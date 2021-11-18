import React, { Component } from "react";

import toastr         from "toastr";

import { LoaderElement } from "@dashboardComponents/Layout/Loader";
import { ButtonIcon }    from "@dashboardComponents/Tools/Button";
import { Aside }         from "@dashboardComponents/Tools/Aside";
import Helper            from "../functions/helper";

import { Step }       from "../Step";
import { Card }       from "./Card";
import { EleveForm }  from "./EleveForm";

function setEleveData(responsable, eleve, isResponsable=true, referral=null){
    let civility = eleve.civility;
    if(eleve.civility === "Mme" || eleve.civility === "Mr" || eleve.civility === "Mme ou Mr" ||eleve.civility === "Mr ou Mme"){
        if(eleve.civility === "Mme"){
            civility = 1;
        }else{
            civility = 0;
        }
    }

    return {
        responsable: responsable.id,
        civility: civility,
        lastname: eleve.lastname,
        firstname: eleve.firstname,
        email: eleve.email,
        emailResponsable: responsable.email,
        phoneMobile: eleve.phone1 ? eleve.phone1 : eleve.phoneMobile,
        birthday: eleve.birthday,
        referral: referral ? referral : null,
        isAncien: eleve.isAncien,
        isResponsable: isResponsable
    }
}

export class Eleves extends Component {
    constructor(props) {
        super();

        this.aside = React.createRef();
        this.eleveForm = React.createRef();

        this.handleBack = this.handleBack.bind(this);
        this.handleNext = this.handleNext.bind(this);
        this.handleAddEleve = this.handleAddEleve.bind(this);
        this.handleOpenAside = this.handleOpenAside.bind(this);
        this.handleDelete = this.handleDelete.bind(this);
    }

    componentDidMount = () => {
        const { profil, elevesUser, type, eleves, responsable, responsableUser } = this.props;

        if(eleves.length === 0){
            if(type !== 2){ // inscription moi ou moi + others
                let find = false;
                if(responsableUser && responsableUser.eleves){
                    responsableUser.eleves.forEach(el => {
                        if(el.firstname.toLowerCase() === responsable.firstname.toLowerCase()
                            && el.lastname.toLowerCase() === responsable.lastname.toLowerCase())
                        {
                            find = el;
                        }
                    })
                }
                let element;
                if(find === false){
                    element = setEleveData(responsable, responsable)
                }else{
                    element = setEleveData(responsable, find, true, find.id)
                }

                this.eleveForm.current.handleUpdateData(element); // update form values to add eleve
                this.eleveForm.current.handleAddEleve(element);
            }

            if(type === 2 || type === 1){ // others ou moi + others
                if(profil === 0 && elevesUser){ // check if Ci eleves user existe
                    elevesUser.forEach(eleve => {
                        let element = setEleveData(responsable, eleve, false, eleve.id)

                        this.eleveForm.current.handleUpdateData(element); // update form values to add eleve
                        this.eleveForm.current.handleAddEleve(element);
                    })
                }
            }
        }

    }

    handleBack = () => { this.props.onChangeContext("responsable") }

    handleNext = (e) => {
        e.preventDefault();

        const { eleves } = this.props;

        if(eleves.length !== 0 ){
            this.props.onChangeContext("review");
        }else{
            toastr.error("Veuillez saisir au moins 1 élève.")
        }

    }

    handleOpenAside = (title, element=null) => {
        this.eleveForm.current.handleUpdateData(element);
        this.aside.current.handleOpen(title);
        this.eleveForm.current.setState({ errors: [] });
    }

    handleAddEleve = (data) => {
        this.props.onAddEleve(data);
        this.aside.current.handleClose();
    }

    handleDelete = (eleve) => { Helper.deleteEleve(this, eleve); }

    render () {
        const { responsable, eleves, type, onCancelBooking } = this.props;

        let liste = [];
        if(eleves){
            // type 2 = others
            // type 1 = moi + other
            liste = eleves.map((elem, index) => {
                return (<div key={index} className="eleve">
                    <Card elem={elem} civility={elem.civility}/>

                    {(type === 2 || !elem.isResponsable) && <div className="eleve-actions">
                        <ButtonIcon icon="pencil" onClick={() => this.handleOpenAside("Modifier " + elem.firstname, elem)}>
                            Modifier
                        </ButtonIcon>
                        <ButtonIcon icon="trash" onClick={() => this.handleDelete(elem)}>Supprimer</ButtonIcon>
                    </div>}
                </div>)
            });
        }

        if(type === 2 && eleves.length === 0){
            liste = null;
        }

        let content = <div className="eleves-content">
            <section>
                <div className="step-title">Liste des élèves souhaitant s'inscrire à la Cité de la musique</div>
                <div className="liste-eleves">
                    <div className="liste-eleves-container">
                        {liste ? (liste.length !== 0 ? liste : <LoaderElement />) : null}

                        {type !== 0 && <button className="add-eleve" onClick={() => this.handleOpenAside("Ajouter un élève")}>
                            <div>
                                <span className="icon-plus" />
                                <span>Ajouter un élève</span>
                            </div>
                        </button>}
                    </div>
                </div>
            </section>
            <Aside ref={this.aside} content={<EleveForm ref={this.eleveForm} responsable={responsable} onAddEleve={this.handleAddEleve}/>}/>
        </div>

        return <div>
            <Step {...this.props} content={content} onClickBack={this.handleBack} onClickNext={this.handleNext} onCancelBooking={onCancelBooking}/>
        </div>
    }
}