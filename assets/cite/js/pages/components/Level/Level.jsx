import React, { Component } from 'react';

import axios               from "axios";
import toastr              from "toastr";
import Swal                from "sweetalert2";
import SwalOptions         from "@commonComponents/functions/swalOptions";
import Routing             from '@publicFolder/bundles/fosjsrouting/js/router.min.js';


import { Page }            from "@dashboardComponents/Layout/Page";
import { LoaderElement }   from "@dashboardComponents/Layout/Loader";
import { Aside }           from "@dashboardComponents/Tools/Aside";
import { Alert }           from "@dashboardComponents/Tools/Alert";
import { SwitcherButton }  from "@dashboardComponents/Tools/Button";
import { Select,
    SelectReactSelectize } from "@dashboardComponents/Tools/Fields";

import Formulaire          from "@dashboardComponents/functions/Formulaire";
import Sort                from "@commonComponents/functions/sort";

import { ClasseList }      from "./ClasseList";
import { EleveFormulaire } from "./EleveForm";

function getDataSortablePaginate(self, url, perPage=10){
    axios.get(url, {})
        .then(function (response) {
            let resp = response.data;

            let data = JSON.parse(resp.data);
            let groups = JSON.parse(resp.groups);

            data.sort(Sort.compareClasseCentre);

            let centres = [], duplicate = [];
            data.forEach(el => {

                el.classe.groups = [];
                groups.forEach(grp => {
                    if(grp.classe.id === el.classe.id){
                        el.classe.groups = [...el.classe.groups, grp]
                    }
                })

                let id = el.classe.centre.id
                if(!duplicate.includes(id)){
                    duplicate.push(id)
                    centres.push({
                        id: id,
                        name: el.classe.centre.name
                    })
                }
            })

            let centre = centres.length !== 0 ? centres[0].id : "";
            self.setState({ dataImmuable: data, data: data, centres: centres, centre: centre });
        })
        .catch(function (error) {
            self.setState({ loadPageError: true });
        })
        .then(function () {
            self.setState({ loadData: false });
        })
    ;
}

function process (self, classe, url, data, elvId) {
    if(classe != null){
        self.setState({ loadElem: elvId })
        axios.post(url, data)
            .then(function (response) {
                let resp = response.data;
                self.handleUpdateClasse(JSON.parse(resp.classe), null, resp.tmGroups, JSON.parse(resp.groups))
                self.page.current.pagination.current.handleComeback()
                self.setState({ loadElem: null })
            })
            .catch(function (error) {
                Formulaire.displayErrors(self, error);
            })
            .then(function () {})
        ;
    }
}

function getUrl(urlName, idGroup, eleve){
    return Routing.generate(urlName, {'group': idGroup, 'eleve': eleve.id})
}

export class Level extends Component {
    constructor(props) {
        super(props);

        this.state = {
            levelIsOpen: props.levelIsOpen,
            context: "list",
            loadPageError: false,
            loadData: true,
            loadElem: null,
            data: null,
            currentData: null,
            element: null,
            filters: [],
            teachers: [],
            teacher: "",
            centres: [],
            centre: "",
            contentAside: "",
            errors: []
        }

        this.page = React.createRef();
        this.aside = React.createRef();
        this.classeList = React.createRef();

        this.handleUpdateData = this.handleUpdateData.bind(this);
        this.handleUpdateClasse = this.handleUpdateClasse.bind(this);
        this.handleLevelUp = this.handleLevelUp.bind(this);
        this.handleStay = this.handleStay.bind(this);
        this.handleFinal = this.handleFinal.bind(this);
        this.handleEdit = this.handleEdit.bind(this);
        this.handleGive = this.handleGive.bind(this);
        this.handleChangeSelect = this.handleChangeSelect.bind(this);
        this.handleChangeSelectCentre = this.handleChangeSelectCentre.bind(this);
        this.handleSwitchManage = this.handleSwitchManage.bind(this);
        this.handleLoadElem = this.handleLoadElem.bind(this);
        this.handleOpenAside = this.handleOpenAside.bind(this);
    }

    componentDidMount() {
        const { role, teacher } = this.props;

        if(role === "admin"){
            const self = this;
            axios.get(Routing.generate('api_teachers_index'), {})
                .then(function (response) {
                    let data = response.data;
                    self.setState({ teachers: data });
                })
                .catch(function (error) {
                    self.setState({ loadPageError: true });
                })
                .then(function () {})
            ;
        }else{
            getDataSortablePaginate(this, Routing.generate('api_classes_provisional_teacher', {'teacher': teacher}))
        }
    }

    handleChangeSelectCentre = (e) => {
        this.setState({ centre: parseInt(e.currentTarget.value) })
    }

    handleChangeSelect = (e) => {
        const { data, teacher } = this.state;
        let value = "";
        if(e !== undefined){
            value = e.value

            if(value !== teacher){
                this.setState({ loadData: true })
                getDataSortablePaginate(this, Routing.generate('api_classes_provisional_teacher', {'teacher': value}))
            }
        }else{
            this.setState({ centre: "" })
        }

        if(data){
            localStorage.setItem("level.pagination", "0")
            this.page.current.pagination.current.handlePageOne();
        }

        this.setState({ teacher: value })
    }

    handleUpdateData = (data) => { this.setState({ currentData: data })  }
    handleUpdateClasse = (classe, elv=null, tmGroups=null, groups) => {
        const { data } = this.state;
        let newData = [];
        data.forEach(elem => {
            if(elem.classe.id === classe.id){
                elem.classe = classe
                elem.classe.groups = groups
                elem.tmGroups = tmGroups
            }

            newData.push(elem)
        })

        this.setState({ dataImmuable: newData, data: newData, loadElem: null });
    }

    handleLevelUp = (idGroup, eleve, classe, from) => {
        if(classe.classes){
            let choice = this.classeList.current.state.choice;
            if(choice !== null){
                if(choice.id === undefined){ // choice classique général (prof + admin option)
                    let choices = [];
                    classe.classes.forEach(el => {
                        if(el.name === choice){
                            choices.push(el)
                        }
                    })
                    process(this, classe, getUrl("api_level_increase_multiple", idGroup, eleve), {'choices': choices, 'from': from.id}, eleve.id);
                }else{ // only admin can select specifique choice
                    process(this, classe, getUrl("api_level_increase", idGroup, eleve), {'classe': choice.id, 'from': from.id}, eleve.id);
                }
            }else{
                toastr.error('Vous devez sélectionner une orientation');
            }
        }else{
            process(this, classe, getUrl("api_level_increase", idGroup, eleve), {'classe': classe.id, 'from': from.id}, eleve.id);
        }
    }

    handleStay = (idGroup, eleve, classe, from) => {
        process(this, classe, getUrl("api_level_keep", idGroup, eleve), {'classe': classe.id, 'from': from.id}, eleve.id);
    }

    handleEdit = (idGroup, eleve, classe, from) => {
        let to = (classe.classes) ? classe.classes : [classe];
        process(this, classe, getUrl("api_level_edit", idGroup, eleve), {'classes': to, 'from': from.id}, eleve.id);
    }

    handleFinal = (idGroup, eleve, classe) => {
        process(this, classe, getUrl("api_level_change_final", idGroup, eleve), {}, eleve.id);
    }

    handleGive = (idGroup, eleve, classe, from) => {
        Swal.fire(SwalOptions.options("Déléguer cet élève ?", "Les professeurs exerçant <u>" + classe.name + "</u> auront la gestion de cet élève. <br><br> " +
            "<b>Etapes à suivre : </b> <br> <span style='display: block; text-align: left; margin-top: 4px;'>" +
            "1 - GESTION DES NIVEAUX du professeur dédié. <br>" +
            "2 - Monter ou Garder l'élève. <br>" +
            "3 - GESTION DES PLANNINGS du professeur dédié. <br>" +
            "4 - Assigner l'élève à un cours.</span>"))
            .then((result) => {
                if (result.isConfirmed) {
                    process(this, classe, getUrl("api_level_give", idGroup, eleve), {'classe': classe.id, 'from': from.id}, eleve.id);
                }
            })
        ;
    }

    handleSwitchManage = () => {
        const { levelIsOpen } = this.state;

        const self = this;
        this.setState({ levelIsOpen: !levelIsOpen });
        axios.post(Routing.generate('api_settings_manage_level'), {})
            .then(function (response) {
                toastr.info(response.data.message);
            })
            .catch(function (error) {
                self.setState({ loadPageError: true });
            })
            .then(function () {})
        ;
    }

    handleLoadElem = (loadElem) => { this.setState({ loadElem }) }

    handleOpenAside = (classe, tmCours, elevesGrp) => {
        this.setState({ contentAside: <EleveFormulaire
                                                    classe={classe} tmCours={tmCours} elevesGrp={elevesGrp}
                                                    onUpdateClasse={this.handleUpdateClasse}
                                                    onComeback={this.page.current.pagination.current.handleComeback}
                                                    onLoadElem={this.handleLoadElem}
                                                    onCloseAside={this.aside.current.handleClose}
            /> });
        this.aside.current.handleOpen(classe.name);
    }

    render () {
        const { role, developer } = this.props;
        const { loadPageError, context, loadData, data, loadElem, teacher, errors, teachers, centres, centre, levelIsOpen, contentAside } = this.state;

        let itemsTeachers = teachers.map(el => {
            return { 'value': el.oldId, 'label': el.lastname + " " + el.firstname, 'identifiant': el.oldId };
        })
        let itemsCentres = centres.map(el => {
            return { 'value': el.id, 'label': el.name, 'identifiant': el.id };
        })

        let toolbar = <div className="toolbar">
            {role === "admin" && <div className="item select">
                <div className="line">
                    <SelectReactSelectize items={itemsTeachers} placeholder="Sélectionner un professeur" identifiant="teacher"
                                          valeur={teacher} errors={errors} onChange={this.handleChangeSelect}/>
                </div>
            </div>}
            <div className="item select">
                <div className="line">
                    <Select items={itemsCentres} placeholder="Sélectionner un centre" identifiant="centre"
                            valeur={centre} errors={errors} onChange={this.handleChangeSelectCentre}/>
                </div>
            </div>
        </div>

        let content = null, havePagination = false;
        switch (context){
            default:
                havePagination = false;
                content = <div>
                    {role === "admin" ? <div className="panel-and-toolbar">
                        <div className="panel-btns">
                            <SwitcherButton status={levelIsOpen} onClick={this.handleSwitchManage}>Gestion des niveaux</SwitcherButton>
                        </div>
                        {toolbar}
                    </div> : <>{toolbar}</>}

                    {loadData ? (teacher !== "" ? <LoaderElement /> : (role === "admin" ? <Alert>Veuillez sélectionner un professeur.</Alert> : <LoaderElement />))
                        : <ClasseList developer={parseInt(developer)} data={data} loadElem={loadElem} role={role} ref={this.classeList} centre={centre}
                                      onLevelUp={this.handleLevelUp} onStay={this.handleStay} onEdit={this.handleEdit} onGive={this.handleGive}
                                      onUpdateClasse={this.handleUpdateClasse} onComeback={this.page.current.pagination.current.handleComeback}
                                      onLoadElem={this.handleLoadElem} onOpenAside={this.handleOpenAside} onFinal={this.handleFinal}
                        />}
                </div>
                break;
        }

        let cont = content;
        let lIsOpen = levelIsOpen === "1";
        if(role !== "admin" && !lIsOpen){
            cont = <Alert>La gestion n'est pas ouverte pour le moment.</Alert>
        }

        return <>
            <Page ref={this.page} haveLoadPageError={loadPageError}
                  havePagination={havePagination} taille={data && data.length} data={data} onUpdate={this.handleUpdateData}
            >
                {cont}
                <Aside ref={this.aside} content={contentAside} />
            </Page>
        </>
    }
}