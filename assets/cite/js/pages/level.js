import "../../css/pages/level.scss";

const routes = require('@publicFolder/js/fos_js_routes.json');
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min';

import React from "react";
import { render } from "react-dom";
import { Levels } from "./components/Level/Levels";

Routing.setRoutingData(routes);

let el = document.getElementById("level");
if(el){
    render(<Levels {...el.dataset} />, el);
}