import React from "react";

export function ButtonIcon(props){
    const { icon, children, text, onClick, element="button", target="_self", tooltipWidth=null } = props;

    let divStyle = tooltipWidth ? { width: tooltipWidth + "px" } : null;

    if(element === "button"){
        return <button className="btn-icon" onClick={onClick}>
            <span className={`icon-${icon}`} />
            {text && <span>{text}</span>}
            {children && <span className="tooltip" style={divStyle}>{children}</span>}
        </button>
    }else{
        return <a className="btn-icon" target={target} href={onClick}>
            <span className={`icon-${icon}`} />
            {text && <span>{text}</span>}
            {children && <span className="tooltip" style={divStyle}>{children}</span>}
        </a>
    }
}

export function Button(props){
    const { icon, type="primary", isSubmit=false, outline=false, children, onClick, element="button", target="_self" } = props;

    if(element === "button"){
        return <button className={`btn btn-${outline ? "outline-" : ""}${type}`} type={isSubmit ? "submit" : "button"} onClick={onClick}>
            {icon && <span className={`icon-${icon}`} />}
            <span>{children}</span>
        </button>
    }else{
        return <a className={`btn btn-${outline ? "outline-" : ""}${type}`} target={target} href={onClick}>
            {icon && <span className={`icon-${icon}`} />}
            <span>{children}</span>
        </a>
    }
}

export function ButtonDropdown(props){
    const { items, children } = props;

    return <div className="btn-dropdown">
        <Button {...props}>{children}</Button>
        <div className="dropdown-items">
            {items.map((item, index) => {
                return <div className="item" key={index}>
                    {item.data}
                </div>
            })}
        </div>
    </div>
}

export function ButtonIconDropdown(props){
    const { items, children } = props;

    return <div className="btn-dropdown">
        <ButtonIcon {...props}>{children}</ButtonIcon>
        <div className="dropdown-items">
            {items.map((item, index) => {
                return <div className="item" key={index}>
                    {item.data}
                </div>
            })}
        </div>
    </div>
}

export function SwitcherButton(props){
    const { status, children, onClick } = props;

    return <div className={"panel-btn" + (status ? " true" : " false")}>
        <div className="title">{children}</div>
        <div className={"actions status-" + (status ? "isOpen" : "isClose")}>
            <div className="status">Ouvert</div>
            <div>
                <button type="submit" className={"switch-btn " + (status ? "isOpen" : "isClose")} onClick={onClick}>
                    <div className="round" />
                </button>
            </div>
            <div className="status">Fermé</div>
        </div>
    </div>
}