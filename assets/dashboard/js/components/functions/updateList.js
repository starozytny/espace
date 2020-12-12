function update(context, data, element){
    let newData = [];

    switch (context){
        case "delete":
            newData = data.filter(el => el.id !== element.id);
            break;
        case "update":
            data.forEach(el => {
                if(el.id === element.id){
                    el = element;
                }
                newData.push(el);
            })
            break;
        default:
            newData = data;
            newData.push(element);
            break;
    }

    return newData;
}

module.exports = {
    update
}