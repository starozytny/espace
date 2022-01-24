import React, { Component } from "react";
import {ClassesItem} from "./ClassesItem";

export class ClassesList extends Component {
    render () {
        const { slot, classes, selectedClasses, onSelectClass } = this.props;

        let items = [];
        classes.forEach(elem => {
            items.push(<ClassesItem key={elem.id} elem={elem} selectedClasses={selectedClasses} slot={slot} onSelectClass={onSelectClass} />)
        })

        return <div className="classes-list">
            <div className="form-title">Liste des classes disponibles</div>
            {items}
        </div>
    }
}