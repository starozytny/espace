import React  from "react";

import Sanitize       from "@commonComponents/functions/sanitaze";

export function Card ({ elem, civility }) {
    return (<div className="eleve-infos">
        <div className="name">{civility}. {elem.firstname} {elem.lastname}</div>
        {elem.email && <div className="email">{elem.email}</div>}
        {elem.phoneMobile && <div className="phone">{Sanitize.toFormatPhone(elem.phoneMobile)}</div>}
        <div className="age">{elem.age} an{elem.age > 1 ? "s" : ""}</div>
        <div className="role">{elem.referral ? (!elem.isAncien ? "Adhérent" : "Ancien adhérent") : "Nouveau" }</div>
    </div>)
}