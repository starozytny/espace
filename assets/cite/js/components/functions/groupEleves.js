const Sort = require("@commonComponents/functions/sort");

function sortGroupEleves(groups, isTm=false){
    let eleves = []
    groups.forEach(item => {
        if(!isTm){
            item.eleve.status = item.status;
            item.eleve.idGroup = item.id;
            item.eleve.isSuspended = item.isSuspended;
            item.eleve.isFm = item.isFm;
            item.eleve.isGiven = item.isGiven;
            item.eleve.tmGroups = item.tmGroups;
            item.eleve.isFinal = item.isFinal;
            item.eleve.renewAnswer = item.renewAnswer;
            item.eleve.canPay = item.eleve ? item.eleve.canPay : null;

            if(item.tmCours){
                item.eleve.tmCours = {
                    id: item.tmCours.id,
                    dayInt: item.tmCours.day,
                    day: item.tmCours.dayString,
                    start: item.tmCours.startShortString
                }
            }

        }else{
            item.eleve.isFm = item.isFm;
            item.eleve.isMultiple = item.isMultiple;
            item.eleve.isHidden = item.isHidden;
            item.eleve.isRefused = item.isRefused;
            item.eleve.isOrphan = item.isOrphan;
            item.eleve.teacherId = item.classeFrom.teacher.id;
            item.eleve.teacher = item.classeFrom.teacher.civility + '. ' + item.classeFrom.teacher.lastname + ' ' + item.classeFrom.teacher.firstname;
            item.eleve.sondage = item.sondage ? item.sondage : null;
            item.eleve.isGiven = item.groupe ? item.groupe.givenGroup : null;
        }
        eleves.push(item.eleve);
    })

    if(eleves.length !== 0){
        eleves.sort(Sort.compareLastname)
    }

    return eleves;
}

function groupByCours(tmGroups){
    let tab = [];

    tmGroups.forEach(item => {
        let find = false;
        tab.forEach(elem => {
            if(elem.oldId === item.tmCours.oldId){
                find = true;
                elem.data.push(createGrpCoursData(item));
            }
        })

        if(!find){
            tab.push({
                oldId: item.tmCours.oldId,
                dayString: item.tmCours.dayString,
                dayInt: item.tmCours.day,
                start: item.tmCours.startString,
                startString: item.tmCours.startString,
                startShortString: item.tmCours.startShortString,
                centre: item.tmCours.centre,
                centreString: item.tmCours.centreString,
                name: item.tmCours.name,
                data: [createGrpCoursData(item)]
            });
        }
    })

    return tab;
}

function createGrpCoursData(item){
    return {
        lastname: item.eleve.lastname,
        eleve: item.eleve
    }
}

module.exports = {
    sortGroupEleves,
    groupByCours
}
