import React, { Component } from 'react';

import { Button }        from "@dashboardComponents/Tools/Button";
import { LessonForm }    from "./LessonForm";

export class LessonCreate extends Component {
    render () {
        const { role, onChangeContext, onUpdateSlot, slot, classes,  elevesVacant, classesVacant } = this.props;

        return <>
            <div>
                <div className="toolbar">
                    <div className="item">
                        <Button icon="left-arrow" outline={true} type="warning" onClick={() => onChangeContext("list")}>Retour au planning</Button>
                    </div>
                </div>

                <div className="form">
                    <h2>Ajouter un cours le {slot.dayString}</h2>
                    <LessonForm
                        type="create"
                        elevesVacant={elevesVacant}
                        classesVacant={classesVacant}
                        role={role}
                        slot={slot}
                        classes={classes}
                        onUpdateSlot={onUpdateSlot}
                        onChangeContext={() => onChangeContext("list")}
                    />
                </div>
            </div>
        </>
    }
}