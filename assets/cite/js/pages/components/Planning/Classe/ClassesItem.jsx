import React, { Component } from "react";

export class ClassesItem extends Component {
    render () {
        const { elem, slot, selectedClasses, onSelectClass } = this.props;

        let active = "";
        selectedClasses.forEach(selectedCl => {
            if(selectedCl.id === elem.id){
                active = " active";
            }
        })

        let canClick = true;

        let maxHtml = elem.max + " élève" + (elem.max > 1 ? 's' : '') + " max";
        let duration = elem.durationLongString ? elem.durationLongString : slot.durationLongString;

        return <div key={elem.id}>
            <div className={"classe" + active}>
                <div className={"classe-infos"} onClick={() => onSelectClass(elem, canClick)}>
                    <div className="radio">
                        <div className="radio-button"/>
                    </div>
                    <div className="infos">
                        <div className="infos-title">
                            <span>[{elem.center.name}]</span>
                            <span>{elem.nameCycleLevel}</span>
                        </div>

                        <div>
                            <div className="time">
                                {elem.mode === 1 ? <span>{duration} / élève ({maxHtml})</span> : <span>{duration} pour {maxHtml}</span>}
                                <span className="icon-time"/>
                            </div>

                            <div className="total">
                                {/*<span>{totalElevesAvailable} élève{totalElevesAvailable > 1 && "s"}</span>*/}
                                <span className="icon-user" />
                            </div>

                            {/*{totalElevesMultiple !== 0 && <div className="total">*/}
                            {/*    <span className="icon-user" />{totalElevesMultiple} en attente de réponse*/}
                            {/*</div>}*/}
                        </div>

                    </div>
                </div>
                <div className="classe-eleves">
                    list des élèves
                </div>
            </div>
        </div>
    }
}