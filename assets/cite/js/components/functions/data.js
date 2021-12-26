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
    axios.get(Routing.generate('api_classes_teacher', {'id': teacherId}), {})
        .then(function (response) {
            switch (cas) {
                case "centers":
                    let classes = response.data;
                    let centers = [];

                    classes.forEach(classe => {
                        let find = false;
                        centers.forEach(center => {
                            if(center.value === classe.center.id){
                                find = true;
                            }
                        })
                        if(!find){
                            centers.push({ value: classe.center.id, label: classe.center.name, identifiant: "center-" + classe.center.id });
                        }
                    })

                    self.setState({ classes: response.data, centers: centers })
                    break;
                default:
                    self.setState({ classes: response.data })
                    break;
            }
        })
        .catch(function (error) {
            Formulaire.displayErrors(self, error);
        })
    ;
}

module.exports = {
    getTeachers,
    getClassesByTeacher
}