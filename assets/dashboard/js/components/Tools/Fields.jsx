import React from "react";

export function Input(props) {
    const { type="text", identifiant, valeur, onChange, children, placeholder } = props;

    let content = <input type={type} name={identifiant} id={identifiant} placeholder={placeholder} value={valeur.value} onChange={onChange}/>

    return (<ClassiqueStructure {...props} content={content} label={children} />)
}

export function Checkbox({items, name, valeur, onChange, children}) {
    let itemsInputs = items.map(elem => {

        valeur.value.map(el => {
            if (el === elem.value){ elem.checked = true }
        })

        return <div className={"checkbox-item " + (elem.checked ? 'checked' : '')} key={elem.id}>
            <label htmlFor={elem.identifiant}>
                {elem.label}
                <input type="checkbox" name={name} id={elem.identifiant} value={elem.value} checked={elem.checked ? 'checked' : ''} onChange={onChange}/>
            </label>
        </div>
    })

    let content = <div className="checkbox-items">{itemsInputs}</div>

    return (<ClassiqueStructure valeur={valeur} identifiant="" content={content} label={children} classForm="form-group-checkbox " />)
}

export function Radiobox({items, name, valeur, onChange, children}) {
    let itemsInputs = items.map(elem => {

        valeur.value.map(el => {
            if (el === elem.value){ elem.checked = true }
        })

        return <div className={"radiobox-item " + (elem.checked ? 'checked' : '')} key={elem.id}>
            <label htmlFor={elem.identifiant}>
                <span>{elem.label}</span>
                <input type="radio" name={name} id={elem.identifiant} value={elem.value} checked={elem.checked ? 'checked' : ''} onChange={onChange}/>
            </label>
        </div>
    })

    let content = <div className="radiobox-items">{itemsInputs}</div>

    return (<ClassiqueStructure valeur={valeur} identifiant="" content={content} label={children} classForm="form-group-radiobox " />)
}

export function TextArea({identifiant, valeur, onChange, rows="8", children}) {
    let content = <textarea name={identifiant} id={identifiant} value={valeur.value} rows={rows} onChange={onChange}/>

    return (<ClassiqueStructure valeur={valeur} identifiant={identifiant} content={content} label={children} />)
}

export function Select({identifiant, valeur, onChange, children, items}) {
    let choices = items.map((item) =>
        <option key={item.value} value={item.value}>{item.libelle}</option>
    )

    let content = <select value={valeur.value} id={identifiant} name={identifiant} onChange={onChange}> {choices} </select>

    return (<ClassiqueStructure valeur={valeur} identifiant={identifiant} content={content} label={children} />)
}

export function Switcher({identifiant, valeur, children, isChecked, onChange}){
    let content = <div className="toggle-wrapper">
        <div className="toggle checkcross">
            <input id={identifiant} name={identifiant} checked={isChecked ? 'checked' : ''} className="input-checkcross" onChange={onChange} type="checkbox"/>
            <label className="toggle-item" htmlFor={identifiant}>
                <div className="check"/>
            </label>
        </div>
    </div>

    return (<ClassiqueStructure valeur={valeur} identifiant={identifiant} content={content} label={children} />)
}

export function Error({valeur}){
    return (
        <div className="error">{valeur.error ? <><span className='icon-warning'/>{valeur.error}</> : null}</div>
    )
}

export function ClassiqueStructure({valeur, identifiant, content, label, classForm=""}){
    return (
        <div className={classForm + 'form-group' + (valeur.error ? " form-group-error" : "")}>
            <label htmlFor={identifiant}>{label}</label>
            {content}
            <Error valeur={valeur}/>
        </div>
    )
}