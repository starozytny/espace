const axios   = require("axios");
const Routing = require("@publicFolder/bundles/fosjsrouting/js/router.min.js");

const Formulaire = require("@dashboardComponents/functions/Formulaire");

function getTeachers(self, cas) {
    axios.get(Routing.generate('api_teachers_index'), {})
        .then(function (response) {
            switch (cas){
                case "select":
                    let selectTeacher = [];
                    response.data.forEach(elem => {
                        selectTeacher.push({ value: elem.id, label: elem.fullname, identifiant: "teacher-" + elem.id })
                    });
                    self.setState({ teachers: selectTeacher });
                    break;
                default:
                    self.setState({ teachers: response.data });
                    break;
            }
        })
        .catch(function (error) {
            self.setState({ loadPageError: true });
            Formulaire.displayErrors(self, error);
        })
        .then(function () {
            self.setState({ loadData: false })
        })
    ;
}

function getClassesByTeacher(self, teacherId, cas) {
    let url = Routing.generate('api_classes_teacher', {'id': teacherId});
    switch (cas) {
        case "centers":
            url = Routing.generate('api_classes_teacher_up', {'id': teacherId})
            break;
        default:
            break;
    }

    Formulaire.loader(true);
    axios.get(url, {})
        .then(function (response) {
            switch (cas) {
                case "centers":
                    let data = JSON.parse(response.data.donnees);
                    let groups = JSON.parse(response.data.groupes);
                    let centers = [];

                    data.forEach(item => {
                        let classe = item.classe;

                        let find = false;
                        centers.forEach(center => {
                            if(center.value === classe.center.id){
                                find = true;
                            }
                        })
                        if(!find){
                            centers.push({ value: classe.center.id, label: classe.center.name, identifiant: "center-" + classe.center.id });
                        }

                        //groupes
                        classe.groups = [];
                        groups.forEach(grp => {
                            if(grp.classe.id === classe.id){
                                classe.groups.push(grp);
                            }
                        })
                    })

                    self.setState({ classes: data, centers: centers })
                    break;
                default:
                    self.setState({ classes: response.data.donnees })
                    break;
            }
        })
        .catch(function (error) {
            Formulaire.displayErrors(self, error);
        })
        .then(function () {
            self.setState({ loadData: false })
            Formulaire.loader(false);
        })
    ;
}

module.exports = {
    getTeachers,
    getClassesByTeacher
}