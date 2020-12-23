import React from "react";

export function Input (props) {
    const { type="text", identifiant, valeur, onChange, children, placeholder } = props;

    let content = <input type={type} name={identifiant} id={identifiant} placeholder={placeholder} value={valeur} onChange={onChange}/>
    return (<ClassiqueStructure {...props} content={content} label={children} />)
}

export function TextArea (props) {
    const { identifiant, valeur, onChange, rows="8", children } = props;

    let content = <textarea name={identifiant} id={identifiant} value={valeur} rows={rows} onChange={onChange}/>
    return (<ClassiqueStructure {...props} content={content} label={children} />)
}

export function Checkbox (props) {
    const {items, identifant, valeur, onChange, children} = props;

    let itemsInputs = items.map(elem => {

        // get checker value
        let isChecked = false
        valeur.map(el => {
            if (el === elem.value){ isChecked = true }
        })

        return <div className={"checkbox-item " + (isChecked ? 'checked' : '')} key={elem.id}>
            <label htmlFor={elem.identifiant}>
                {elem.label}
                <input type="checkbox" name={identifant} id={elem.identifiant} value={elem.value} checked={isChecked ? 'checked' : ''} onChange={onChange}/>
            </label>
        </div>
    })

    let content = <div className="checkbox-items">{itemsInputs}</div>
    return (<ClassiqueStructure {...props} content={content} label={children} classForm="form-group-checkbox " />)
}

export function Radiobox(props) {
    const {items, identifiant, valeur, onChange, children} = props;

    let itemsInputs = items.map(elem => {

        let isChecked = false
        if (parseInt(valeur) === elem.value){ isChecked = true }

        return <div className={"radiobox-item " + (isChecked ? 'checked' : '')} key={elem.id}>
            <label htmlFor={elem.identifiant}>
                <span>{elem.label}</span>
                <input type="radio" name={identifiant} id={elem.identifiant} value={elem.value} checked={isChecked ? 'checked' : ''} onChange={onChange}/>
            </label>
        </div>
    })

    let content = <div className="radiobox-items">{itemsInputs}</div>

    return (<ClassiqueStructure {...props} content={content} label={children} classForm="form-group-radiobox " />)
}

export function Select({identifiant, valeur, onChange, children, items}) {
    let choices = items.map((item) =>
        <option key={item.value} value={item.value}>{item.libelle}</option>
    )

    let content = <select value={valeur} id={identifiant} name={identifiant} onChange={onChange}> {choices} </select>
    return (<ClassiqueStructure {...props} content={content} label={children} />)
}

export function Switcher({identifiant, valeur, children, isChecked, onChange}){
    let content = <div className="toggle-wrapper">
        <div className="toggle checkcross">
            <input id={identifiant} name={identifiant} checked={isChecked ? 'checked' : ''} value={valeur} className="input-checkcross" onChange={onChange} type="checkbox"/>
            <label className="toggle-item" htmlFor={identifiant}>
                <div className="check"/>
            </label>
        </div>
    </div>

    return (<ClassiqueStructure {...props} content={content} label={children} />)
}


export function ClassiqueStructure({identifiant, content, errors, label, classForm=""}){

    let error;
    if(errors.length !== 0){
        errors.map(err => {
          if(err.name === identifiant){
              error = err.message
          }
        })
    }

    return (
        <div className={classForm + 'form-group' + (error ? " form-group-error" : "")}>
            <label htmlFor={identifiant}>{label}</label>
            {content}
            <div className="error">{error ? <><span className='icon-warning'/>{error}</> : null}</div>
        </div>
    )
}