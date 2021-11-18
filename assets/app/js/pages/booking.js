import '../../css/pages/booking.scss';

const routes = require('@publicFolder/js/fos_js_routes.json');
import Routing          from '@publicFolder/bundles/fosjsrouting/js/router.min';

import React            from "react";
import { render }       from "react-dom";

import { Booking }      from "./components/Booking/Booking";
import { MyBooking } from "./components/Booking/MyBooking";

Routing.setRoutingData(routes);

let el = document.getElementById("booking");
if(el){
    render(<Booking {...el.dataset} />, el);
}

el = document.getElementById("myBooking");
if(el){
    render(<MyBooking {...el.dataset} />, el);
}