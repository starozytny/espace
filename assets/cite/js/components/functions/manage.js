const axios   = require("axios");
const toastr  = require("toastr");
const Routing = require("@publicFolder/bundles/fosjsrouting/js/router.min.js");

function switchLevel (self, isOpen) {
    axios.post(Routing.generate('api_settings_manage_level'), {})
        .then(function (response) {
            toastr.info(response.data.message);
            self.setState({ isOpen: !isOpen });
        })
        .catch(function (error) {
            self.setState({ loadPageError: true });
        })
    ;
}

module.exports = {
    switchLevel
}