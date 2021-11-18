const axios      = require("axios");
const Formulaire = require("@dashboardComponents/functions/Formulaire");
const Routing    = require("@publicFolder/bundles/fosjsrouting/js/router.min");

function newArrayEleves(prevState, data){
    let newEleves = [];
    let found = false;
    prevState.eleves.forEach(el => {
        if(el.id === data.id){
            el = data;
            found = true;
        }

        newEleves.push(el);
    })

    if(!found){
        newEleves.push(data);
    }

    return newEleves;
}

function deleteEleve(self, eleve) {
    Formulaire.loader(true);
    axios.delete(Routing.generate('api_booking_eleves_delete', {'id': eleve.id}), {})
        .then(function (response) {
            self.props.onDeleteEleve(eleve);
        })
        .catch(function (error) {
            Formulaire.displayErrors(self, error, "Une erreur est survenue, veuillez rafraichir la page.")
        })
        .then(() => {
            Formulaire.loader(false);
        })
    ;
}

module.exports = {
    newArrayEleves,
    deleteEleve
}