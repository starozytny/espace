const routes = require('@publicFolder/js/fos_js_routes.json');
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min';

import React from 'react';
import { render } from 'react-dom';
import { BBB } from './components/Visio/Visio';

Routing.setRoutingData(routes);

let el = document.getElementById("join-bbb");
if(el){
    render(<BBB {...el.dataset}/>, el)
}