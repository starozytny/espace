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

module.exports = {
    getTeachers
}