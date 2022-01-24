import React, { Component } from 'react';

import { Button }        from "@dashboardComponents/Tools/Button";
import { LessonForm }    from "./LessonForm";
import TimeHelper        from "@citeComponents/functions/timeHelper";

export class LessonUpdate extends Component {
    render () {
        const { role, onChangeContext, onUpdateSlot, slot, lesson, classes, elevesVacant, classesVacant } = this.props;

        return <>
            <div>
                <div className="toolbar">
                    <div className="item">
                        <Button icon="left-arrow" outline={true} type="warning" onClick={() => onChangeContext("list")}>Retour au planning</Button>
                    </div>
                </div>

                <div className="form">
                    <h2>Modifier {lesson.nameLesson} de {TimeHelper.betterDisplayTimeNotDot(lesson.startString)} à {TimeHelper.betterDisplayTimeNotDot(lesson.endString)} le {slot.dayString}
                    </h2>
                    <LessonForm
                        type="update"
                        elevesVacant={elevesVacant}
                        classesVacant={classesVacant}
                        role={role}
                        slot={slot}
                        lesson={lesson}
                        classes={classes}
                        onUpdateSlot={onUpdateSlot}
                        onChangeContext={() => onChangeContext("list")}
                    />
                </div>
            </div>
        </>
    }
}