import React, { Component } from "react";

import axios               from "axios";
import Swal                from "sweetalert2";
import toastr              from "toastr";
import SwalOptions         from "@commonComponents/functions/swalOptions";
import Routing             from "@publicFolder/bundles/fosjsrouting/js/router.min";

import { Input, Radiobox } from "@dashboardComponents/Tools/Fields";
import { Alert }           from "@dashboardComponents/Tools/Alert";
import { DatePick }        from "@dashboardComponents/Tools/DatePicker";
import { Button }          from "@dashboardComponents/Tools/Button";
import Validateur          from "@commonComponents/functions/validateur";
import Formulaire          from "@dashboardComponents/functions/Formulaire";

import { Step }   from "../Step";
import { Forget } from "../../Security/Forget";

function setDataResponsable(responsableUser)
{
    if(responsableUser){
        let civility = null;
        if(responsableUser.civility === "Mr"){
            civility = 0;
        }else if(responsableUser.civility === "Mme"){
            civility = 1;
        }

        return {
            referral: responsableUser.oldId ? responsableUser.oldId : responsableUser.id,
            lastname: responsableUser.lastname ? responsableUser.lastname : "",
            firstname: responsableUser.firstname ? responsableUser.firstname : "",
            email: responsableUser.email ? responsableUser.email : "",
            emailConfirm: responsableUser.email ? responsableUser.email : "",
            email2: responsableUser.email2 ? responsableUser.email2 : "",
            phone1: responsableUser.phone1 ? responsableUser.phone1 : "",
            phone2: responsableUser.phone2 ? responsableUser.phone2 : "",
            phone3: responsableUser.phone3 ? responsableUser.phone3 : "",
            infoPhone1: responsableUser.infoPhone1 ? responsableUser.infoPhone1 : "",
            infoPhone2: responsableUser.infoPhone2 ? responsableUser.infoPhone2 : "",
            infoPhone3: responsableUser.infoPhone3 ? responsableUser.infoPhone3 : "",
            adr: responsableUser.adr ? responsableUser.adr : "",
            complement: responsableUser.complement ? responsableUser.complement : "",
            cp: responsableUser.cp ? responsableUser.cp : "",
            city: responsableUser.city ? responsableUser.city : "",
            civility: civility,
            addPhone2: !!responsableUser.phone2,
            addPhone3: !!(responsableUser.phone2 && responsableUser.phone3)
        }
    }
}

export class Responsable extends Component {
    constructor(props) {
        super(props);

        let birthday = props.responsable.birthday ? props.responsable.birthday : null;
        if(props.responsableUser && birthday === null){
            if(props.responsableUser.eleves.length === 1){
                birthday = new Date(props.responsableUser.eleves[0].birthday);
            }else if(props.responsableUser.eleves.length > 1){
                props.responsableUser.eleves.forEach(eleve => {
                    if(eleve.firstname === props.responsableUser.firstname && eleve.lastname === props.responsableUser.lastname){
                        birthday = new Date(eleve.birthday);
                    }
                })
            }
        }

        this.state = {
            type: props.type,
            profil: props.profil,
            errors: [],
            id: props.responsable.oldId ? props.responsable.oldId : props.responsable.id,
            referral: props.responsableUser ? props.responsableUser.id : "",
            lastname: props.responsable.lastname ? props.responsable.lastname : "",
            firstname: props.responsable.firstname ? props.responsable.firstname : "",
            email: props.responsable.email ? props.responsable.email : "",
            email2: props.responsable.email2 ? props.responsable.email2 : "",
            emailConfirm: props.responsable.emailConfirm ? props.responsable.emailConfirm : "",
            phone1: props.responsable.phone1 ? props.responsable.phone1 : "",
            phone2: props.responsable.phone2 ? props.responsable.phone2 : "",
            phone3: props.responsable.phone3 ? props.responsable.phone3 : "",
            infoPhone1: props.responsable.infoPhone1 ? props.responsable.infoPhone1 : "",
            infoPhone2: props.responsable.infoPhone2 ? props.responsable.infoPhone2 : "",
            infoPhone3: props.responsable.infoPhone3 ? props.responsable.infoPhone3 : "",
            adr: props.responsable.adr ? props.responsable.adr : "",
            complement: props.responsable.complement ? props.responsable.complement : "",
            cp: props.responsable.cp ? props.responsable.cp : "",
            city: props.responsable.city ? props.responsable.city : "",
            civility: props.responsable.civility ? props.responsable.civility : "",
            birthday: birthday,
            sameEmail: 0,
            addPhone2: false,
            addPhone3: false,
            canEditReferral: false,
            password: ""
        }

        this.handleChange = this.handleChange.bind(this);
        this.handleChangeDateBirthday = this.handleChangeDateBirthday.bind(this);
        this.handleChangePostalCodeCity = this.handleChangePostalCodeCity.bind(this);
        this.handleBack = this.handleBack.bind(this);
        this.handleNext = this.handleNext.bind(this);
        this.handleAddPhone = this.handleAddPhone.bind(this);
        this.handleReferral = this.handleReferral.bind(this);
        this.handleEditReferral = this.handleEditReferral.bind(this);
    }

    componentDidMount = () => {
        const { responsableUser } = this.props;

        this.setState(setDataResponsable(responsableUser));
    }

    handleChange = (e) => { this.setState({ [e.currentTarget.name]: e.currentTarget.value }) }
    handleChangeDateBirthday= (e) => { this.setState({ birthday: e }) }
    handleChangePostalCodeCity = (e) => {
        const { arrayPostalCode } = this.props;

        let name = e.currentTarget.name;
        let value = e.currentTarget.value;

        if(value.length <= 5){
            this.setState({ [name]: value })

            let v = ""
            if(arrayPostalCode.length !== 0){
                v = arrayPostalCode.filter(el => el.cp === value)

                if(v.length === 1){
                    this.setState({ city: v[0].city })
                }
            }
        }
    }

    handleBack = () => {
        const { id } = this.state;

        let self = this;
        Swal.fire(SwalOptions.options('Retour au choix du profil', 'Les informations du responsable et des élèves seront perdues.'))
            .then((result) => {
                if (result.isConfirmed) {

                    Formulaire.loader(true);
                    axios.delete(Routing.generate('api_booking_responsable_delete', {'id': id}), {})
                        .then(function (response) {
                            self.props.onChangeContext("profil")
                            self.props.onUpdateResponsable(null); // clear delete responsable bo
                            // self.props.onUpdateUser(null);
                        })
                        .catch(function (error) {
                            Formulaire.displayErrors(self, error, "Une erreur est survenue, veuillez rafraichir la page.")
                        })
                        .then(() => {
                            Formulaire.loader(false);
                        })
                    ;
                }
            })
        ;
    }

    handleNext = (e) => {
        e.preventDefault();

        const { responsableUser, profil, type } = this.props;
        const { referral, id, lastname, firstname, email, email2, emailConfirm, sameEmail,
            phone1, adr, cp, city, civility, birthday } = this.state;

        if((responsableUser === null || responsableUser === "") && profil === 0){
            let msg = "Veuillez rechercher votre compte avant de continuer la procédure.";
            toastr.error(msg)
            this.setState({errors: [{
                    name: "referral",
                    message: msg
                }]});
        }else{
            // validate global
            let paramsToValidate = [
                {type: "text", id: 'civility', value: civility},
                {type: "text", id: 'lastname', value: lastname},
                {type: "text", id: 'firstname', value: firstname},
                {type: "text", id: 'adr', value: adr},
                {type: "text", id: 'cp', value: cp},
                {type: "text", id: 'city', value: city},
                {type: "text", id: 'phone1', value: phone1},
                {type: "emailConfirm", id: 'email', value: email, idCheck: 'emailConfirm', valueCheck: emailConfirm},
            ];

            if(profil === 0){
                paramsToValidate = [...paramsToValidate,
                    ...[{type: "text", id: 'referral', value: referral}]
                ];
            }

            if(type !== 2){
                paramsToValidate = [...paramsToValidate,
                    ...[{type: "date", id: 'birthday', value: birthday}]
                ];
            }

            if(sameEmail === 1){
                paramsToValidate = [...paramsToValidate,
                    ...[{type: "text", id: 'email2', value: email2}]
                ];
            }

            let validate = Validateur.validateur(paramsToValidate)

            //check validate success
            if(!validate.code) {
                this.setState({errors: validate.errors});
                toastr.error("Veuillez vérifier que tous les champs obligatoires sont renseignés")
            }else{
                Formulaire.loader(true);
                let self = this;
                axios({ method: "PUT", url: Routing.generate('api_booking_responsable_update', {"id": id}), data: self.state })
                    .then(function (response) {
                        self.props.onUpdateResponsable(self.state);
                        self.props.onChangeContext("eleves");
                    })
                    .catch(function (error) {
                        Formulaire.displayErrors(self, error, "Une erreur est survenue, veuillez rafraichir la page.")
                    })
                    .then(() => {
                        Formulaire.loader(false);
                    })
                ;
            }
        }
    }

    handleAddPhone = (number) => {
        if(number === 2){
            this.setState({ addPhone2: true})
        }else if(number === 3){
            this.setState({ addPhone3: true})
        }
    }

    handleReferral = () => {
        const { referral, password } = this.state;

        if(referral !== "" && password !== ""){
            Formulaire.loader(true);
            let self = this;
            axios({ method: "POST", url: Routing.generate('api_booking_responsable_existe', {"referral": referral}), data: {password: password} })
                .then(function (response) {
                    let data = response.data;

                    self.props.onUpdateResponsable(data, true);
                    self.props.onUpdateUser(data);
                    self.setState(setDataResponsable(data));
                    self.setState({ errors: [], canEditReferral: true })
                })
                .catch(function (error) {
                    Formulaire.displayErrors(self, error, "Une erreur est survenue, veuillez rafraichir la page.");
                })
                .then(() => {
                    Formulaire.loader(false);
                })
            ;
        }else{
            toastr.error("Veuillez saisir votre identifiant et mot de passe pour continuer.")
            this.setState({errors: [{
                name: "referral",
                message: "L'identifiant et le mot de passe doivent être remplis."
            }, { name: "password", message: "L'identifiant et le mot de passe doivent être remplis." }]});

        }
    }

    handleEditReferral = () => {
        this.props.onUpdateResponsable(null);
        this.props.onUpdateUser(null);
        this.setState({ referral: "", canEditReferral: false })
    }

    render () {
        const { user, responsableUser, profil, type, onCancelBooking } = this.props;
        const { canEditReferral, errors, referral, password, lastname, firstname, email, email2, emailConfirm, sameEmail, addPhone2, addPhone3,
                phone1, phone2, phone3, infoPhone1, infoPhone2, infoPhone3, adr, complement, cp, city, civility, birthday } = this.state;

        let civilityItems = [
            { value: 0, label: 'Mr', identifiant: 'monsieur' },
            { value: 1, label: 'Mme', identifiant: 'madame' },
        ]

        let responseEmail = [
            { value: 1, label: 'Oui', identifiant: 'response-1' },
            { value: 0, label: 'Non', identifiant: 'response-0' },
        ]

        let divStyle = {
            marginTop: "12px",
            cursor: "pointer"
        }

        let numeroAdherent = null;
        if(user){
            numeroAdherent = <div className="line">
                <label>Numéro adhérent</label>
                <p>{referral}</p>
            </div>
        }else{
            if(profil === 0){
                if(canEditReferral){
                    numeroAdherent = <>
                        <div className="line">
                            <label>Numéro adhérent</label>
                            <p>{referral}</p>
                        </div>
                        <div className="line">
                            <Button type="default" onClick={this.handleEditReferral}>Modifier le numéro adhérent</Button>
                        </div>
                    </>
                }else{
                    numeroAdherent = <>
                        <div className="line line-2">
                            <Input identifiant="referral" valeur={referral} errors={errors} onChange={this.handleChange} placeholder="r-XXXXXX">Identifiant</Input>
                            <Input type="password" identifiant="password" valeur={password} errors={errors} onChange={this.handleChange}>Mot de passe</Input>
                        </div>
                        <div className="line">
                            <Button type="warning" onClick={this.handleReferral}>Retrouver mon compte</Button>
                            <div style={divStyle}>
                                <Forget />
                            </div>
                        </div>
                    </>
                }
            }
        }

        let form = <form className="form-resp-infos" onSubmit={this.handleNext}>
            <div className="resp-infos-general">
                <div className="line line-2">
                    <Input identifiant="firstname" valeur={firstname} errors={errors} onChange={this.handleChange}>Prénom</Input>
                    <Input identifiant="lastname" valeur={lastname} errors={errors} onChange={this.handleChange}>Nom</Input>
                </div>
                <div className={"line" + (type !== 2 ? " line-2" : "")}>
                    <Radiobox items={civilityItems} identifiant="civility" valeur={civility} errors={errors} onChange={this.handleChange}>Civilité</Radiobox>
                    {type !== 2 && <DatePick identifiant="birthday" valeur={birthday} maxDate={new Date()} errors={errors} onChange={this.handleChangeDateBirthday}>Date de naissance</DatePick>}
                </div>
            </div>

            <div className="resp-infos-contact">
                <div className="line">
                    <div className="form-group">
                        <Alert type="warning">Le ticket sera envoyé à l'adresse e-mail ci-dessous.</Alert>
                    </div>
                </div>
                <div className="line line-2">
                    <Input type="email" identifiant="email" valeur={email} errors={errors} onChange={this.handleChange}>Adresse e-mail pour recevoir le ticket</Input>
                    <Input type="email" identifiant="emailConfirm" valeur={emailConfirm} errors={errors} onChange={this.handleChange}>Confirmer l'adresse e-mail</Input>
                </div>
                <div className="line">
                    <Radiobox items={responseEmail} identifiant="sameEmail" valeur={sameEmail} errors={errors} onChange={this.handleChange}>
                        <b>Au cours de l'année <u>(pour vous contacter)</u></b>, l'adresse e-mail utilisée ci-dessus pour la réservation de votre ticket, sera-t-elle différente ?
                    </Radiobox>
                </div>
                {parseInt(sameEmail) === 1 && <div className="line">
                    <Input type="email" identifiant="email2" valeur={email2} errors={errors} onChange={this.handleChange}>Adresse e-mail de contact</Input>
                </div>}
            </div>

            <div className="resp-infos-phone">
                <div className="line line-2">
                    <Input type="number" identifiant="phone1" valeur={phone1} errors={errors} onChange={this.handleChange}>Téléphone <b>principal</b></Input>
                    <Input identifiant="infoPhone1" valeur={infoPhone1} errors={errors} onChange={this.handleChange}>A qui appartient ce numéro ?</Input>
                </div>
                {addPhone2 && <div className="line line-2">
                    <Input type="number" identifiant="phone2" valeur={phone2} errors={errors} onChange={this.handleChange}>Téléphone 2</Input>
                    <Input identifiant="infoPhone2" valeur={infoPhone2} errors={errors} onChange={this.handleChange}>A qui appartient ce numéro ?</Input>
                </div>}
                {addPhone3 && <div className="line line-2">
                    <Input type="number" identifiant="phone3" valeur={phone3} errors={errors} onChange={this.handleChange}>Téléphone 3</Input>
                    <Input identifiant="infoPhone3" valeur={infoPhone3} errors={errors} onChange={this.handleChange}>A qui appartient ce numéro ?</Input>
                </div>}
                {!addPhone2 && <div className="line">
                    <div className="btn-add-phone" onClick={() => this.handleAddPhone(2)}>Ajouter un téléphone</div>
                </div>}
                {addPhone2 && !addPhone3 && <div className="line">
                    <div className="btn-add-phone" onClick={() => this.handleAddPhone(3)}>Ajouter un téléphone</div>
                </div>}
            </div>

            <div className="resp-infos-address">
                <div className="line line-2">
                    <Input identifiant="adr" valeur={adr} errors={errors} onChange={this.handleChange}>Adresse</Input>
                    <Input identifiant="complement" valeur={complement} errors={errors} onChange={this.handleChange}>Complément (facultatif)</Input>
                </div>
                <div className="line line-2">
                    <Input type="number" identifiant="cp" valeur={cp} errors={errors} onChange={this.handleChangePostalCodeCity}>Code postal</Input>
                    <Input identifiant="city" valeur={city} errors={errors} onChange={this.handleChange}>Ville</Input>
                </div>
            </div>
        </form>

        if((responsableUser === null || responsableUser === "") && profil === 0){
            form = null;
        }

        let content = <div className="responsable-content">
            <section>
                <div className="step-col-1">
                    <div className="step-title">Saisir les informations du référent</div>
                    <div className="step-title-infos">
                        <p>
                            Le référent désigne la personne avec qui la Cité de la Musique pourra communiquer tout au long de l'année.
                        </p>
                    </div>

                    {numeroAdherent}

                    <div className="form-rgpd-txt alert alert-default">
                        <span className="icon-information" />
                        <p>
                            Les données à caractère personnel collectées dans ce formulaire d'inscription, par la
                            Cité de la Musique de Marseille sont enregistrées dans un fichier informatisé permettant
                            la gestion des dossiers. Ce fichier est hébergé en France par OVH , la Société Logilink
                            assure la maintenance de notre serveur et la sécurité de celui-ci. <br/>
                            Par cette inscription, vous consentez à nous transmettre vos données pour vous offrir ce service.
                            <br/>
                            Plus d'informations sur le traitement de vos données dans notre <a href={Routing.generate('app_politique')}>politique de confidentialité</a>.
                        </p>
                    </div>

                </div>

                {form}

            </section>

        </div>

        return <div>
            <Step {...this.props} content={content} txtBack="Retour au profil"
                  onClickBack={this.handleBack} onClickNext={this.handleNext} onCancelBooking={onCancelBooking}/>
        </div>
    }
}