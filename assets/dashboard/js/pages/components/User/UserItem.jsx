import React, { Component } from 'react';

import Routing          from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { ButtonIcon }   from "@dashboardComponents/Tools/Button";
import { Selector }     from "@dashboardComponents/Layout/Selector";

export class UserItem extends Component {
    render () {
        const { developer, elem, onChangeContext, onDelete, onSelectors, onRegenerate } = this.props

        let routeName = 'user_homepage'
        if(elem.highRoleCode === 2){
            routeName = 'admin_homepage'
        }else if(elem.highRoleCode === 3){
            routeName = 'teacher_homepage'
        }else if(elem.highRoleCode === 4){
            routeName = 'responsable_homepage'
        }

        let url = Routing.generate(routeName, {'_switch_user' : elem.username})

        let avatar = (elem.avatar) ? "/avatars/" + elem.avatar : `https://robohash.org/${elem.username}?size=64x64`;

        return <div className="item">
            <Selector id={elem.id} onSelectors={onSelectors} />

            <div className="item-content">
                <div className="item-body">
                    <div className="infos infos-col-3">
                        <div className="col-1">
                            <div className="name">
                                <span>{elem.lastname.toUpperCase()} {elem.firstname}</span>
                                {elem.highRoleCode !== 0 && <span className="role">{elem.highRole}</span>}
                            </div>
                            {elem.highRoleCode !== 1 && elem.lastLoginAgo && <div className="sub">Connecté {elem.lastLoginAgo}</div>}
                        </div>
                        <div className="col-2">
                            <div className="sub sub-username">{elem.username}</div>
                            {elem.email ? <div className="sub">{elem.email}</div> : <div className="sub txt-danger"><span className="icon-warning" /> {elem.email}</div>}
                            {elem.fullAncien && <div className="sub">Ancien</div>}
                        </div>
                        <div className="col-3 actions">
                            {elem.highRoleCode !== 1 &&
                            <>
                                <ButtonIcon icon="pencil" onClick={() => onChangeContext("update", elem)}>Modifier</ButtonIcon>
                                <ButtonIcon icon="trash" onClick={() => onDelete(elem)}>Supprimer</ButtonIcon>
                                {developer === 1 && <ButtonIcon icon="share" element="a" target="_blank" onClick={url}>Imiter</ButtonIcon>}
                                <ButtonIcon icon="settings" onClick={() => onRegenerate(elem)}>Regénérer un mot de passe</ButtonIcon>
                            </>
                            }
                        </div>
                    </div>
                </div>
            </div>
        </div>
    }
}