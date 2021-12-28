function formatTime(temps){
    return (temps < 10) ? "0" + temps : temps
}

function addBySoixante(timeA, timeB){
    let retenu = 0;
    let temps = timeA + timeB;

    if(temps >= 60){
        retenu = 1
        temps = temps - 60

        if(temps === 60){
            retenu = 2
            temps = 0
        }
    }
    return [temps, retenu]
}

function iterationAddTime(iteration, startString, dureeString){
    let newEnd = null;

    for(let i = 0 ; i < iteration ; i++){
        if(startString && dureeString){
            newEnd = addTime(startString, dureeString)
            startString = newEnd
        }
    }

    return newEnd;
}

function addTime(startString, dureeString){
    let startTime = startString.split(':');
    let st_h = parseInt(startTime[0]), st_m = parseInt(startTime[1]), st_s = parseInt(startTime[2]);

    let elvTime = dureeString.split(':')
    let elv_h = parseInt(elvTime[0]), elv_m = parseInt(elvTime[1]), elv_s = parseInt(elvTime[2]);

    let tabSecondes = addBySoixante(st_s, elv_s)
    let s = tabSecondes[0]

    st_m = tabSecondes[1] + st_m
    let tabMinutes = addBySoixante(st_m, elv_m)
    let m = tabMinutes[0]

    st_h = tabMinutes[1] + st_h
    let h = st_h + elv_h

    return formatTime(h) + ':' + formatTime(m) + ':' + formatTime(s)
}

function calculateTime(mode, nbEleves, startString, dureeString, endMax){
    let iteration = (mode === 1) ? nbEleves : 1;
    let newEnd = iterationAddTime(iteration, startString, dureeString)

    if( newEnd > endMax){
        return false
    }

    return newEnd
}

function betterDisplayTime(time){
    if(time === null){
        return "?";
    }
    return time.substring(0, 5);
}

function betterDisplayTimeNotDot(time){
    if(time === null){
        return "?";
    }
    return time.substring(0, 5).replaceAll(':', 'h');
}

function getMillisByPattern(timer){
    let parts = timer.split(/:/);

    return (parseInt(parts[0], 10) * 60 * 60 * 1000) +
        (parseInt(parts[1], 10) * 60 * 1000) +
        (parseInt(parts[2], 10) * 1000);
}

module.exports = {
    calculateTime,
    betterDisplayTime,
    betterDisplayTimeNotDot,
    getMillisByPattern
}
