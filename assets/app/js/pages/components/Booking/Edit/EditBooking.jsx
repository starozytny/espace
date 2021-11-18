import React, { Component } from "react";

import Sanitize          from "@commonComponents/functions/sanitaze";
import { Aside }         from "@dashboardComponents/Tools/Aside";
import { ButtonIcon }    from "@dashboardComponents/Tools/Button";
import Helper            from "../functions/helper";

import { Card }          from "../Steps/Card";
import { TicketContent } from "../Steps/Ticket";
import { EleveForm }     from "../Steps/EleveForm";

export class EditBooking extends Component {
    constructor(props) {
        super();

        this.aside = React.createRef();
        this.eleveForm = React.createRef();

        this.handleOpenAside = this.handleOpenAside.bind(this);
        this.handleAddEleve = this.handleAddEleve.bind(this);
        this.handleDelete = this.handleDelete.bind(this);
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
        const { responsable, eleves } = this.props;

        let content = <div className="review-content">
            <section>
                <div className="liste-review">
                    <div className="liste-review-responsable">
                        <div className="liste-review-title">Référent</div>
                        <div className="responsable">
                            <div className="eleve-infos">
                                <div className="name">{responsable.civility}. {responsable.firstname} {responsable.lastname}</div>
                                <div className="email">{responsable.email}</div>
                                <div className="phone">{Sanitize.toFormatPhone(responsable.phone1)}</div>
                            </div>
                            <div className="eleve-actions">
                                <p>
                                    Pour modifier les informations du référent, veuillez contacter par mail la Cité de la musique Marseille. <br/>
                                    contact@citemusique-marseille.com
                                </p>
                            </div>
                        </div>
                    </div>
                    <div className="liste-review-eleves">
                        <div className="liste-review-title">Les élèves concernés</div>
                        {eleves && eleves.map((elem, index) => {
                            return <div key={index} className="eleve">
                                <Card elem={elem} civility={elem.civility}/>

                                {eleves.length > 1 && <div className="eleve-actions">
                                    <ButtonIcon icon="pencil" onClick={() => this.handleOpenAside("Modifier " + elem.firstname, elem)}>
                                        Modifier
                                    </ButtonIcon>
                                    <ButtonIcon icon="trash" onClick={() => this.handleDelete(elem)}>Supprimer</ButtonIcon>
                                </div>}
                            </div>
                        })}
                        <button className="add-eleve" onClick={() => this.handleOpenAside("Ajouter un élève")}>
                            <div>
                                <span className="icon-plus" />
                                <span>Ajouter un élève</span>
                            </div>
                        </button>
                    </div>
                </div>
                <TicketContent responsable={responsable}/>
                <Aside ref={this.aside} content={<EleveForm ref={this.eleveForm} responsable={responsable} onAddEleve={this.handleAddEleve}/>}/>
            </section>
        </div>

        return <div className="edit-booking">
            {content}
        </div>
    }
}