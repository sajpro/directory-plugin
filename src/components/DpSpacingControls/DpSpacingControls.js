import { BaseControl } from "@wordpress/components";
import { FaLink} from 'react-icons/fa';
import { __ } from "@wordpress/i18n";
import {DpUnits} from "../DpUnits"
import DpResponsiveControls from "../DpResponsiveControls/DpResponsiveControls"

import { useGetDevice } from "../../utils/getDevice";

export const DpSpacingControls = (props) => {
    let { attributes, setAttributes,attributesId, className, label } = props;

    const getDevice = useGetDevice();

    const handleOnChange = (e) => {

        let key = e.target.name;
        let value = e.target.value;

        if(attributes[attributesId][getDevice]['isLinked']){
            setAttributes({[attributesId]: {...attributes[attributesId], [getDevice]: {
                ...attributes[attributesId][getDevice],
                top: value,
                right: value,
                bottom: value,
                left: value
            }}})
        }else{
            setAttributes({[attributesId]: {...attributes[attributesId], [getDevice]: {
                ...attributes[attributesId][getDevice],
                [key]: (value || 0)
            }}})
        }
    }

    return (
        <BaseControl
            label={label}
            className={className}
        >
            <div className="units-responsive">
                <DpResponsiveControls {...{attributes, setAttributes,attributesId}}/>
                <DpUnits {...{attributes, setAttributes,attributesId}}/>
            </div>

            <div className="dp-space-controls">
                <ul>
                    <li>
                        <input type="number" name="top" onChange={handleOnChange} value={attributes[attributesId][getDevice]['top']}/>
                        <span>{__('Top','directory-plugin')}</span>
                    </li>
                    <li>
                        <input type="number" name="right" onChange={handleOnChange} value={attributes[attributesId][getDevice]['right']} />
                        <span>{__('Right','directory-plugin')}</span>
                    </li>
                    <li>
                        <input type="number" name="bottom" onChange={handleOnChange} value={attributes[attributesId][getDevice]['bottom']}/>
                        <span>{__('Bottom','directory-plugin')}</span>
                    </li>
                    <li>
                        <input type="number" name="left" onChange={handleOnChange} value={attributes[attributesId][getDevice]['left']}/>
                        <span>{__('Left','directory-plugin')}</span>
                    </li>
                    <li>
                        <button
                            type="submit"
                            className={ `global-btn ${attributes[attributesId][getDevice]['isLinked'] ? '' : 'no'}` }
                            onClick={ () => {
                                let top = attributes[attributesId][getDevice].top
                                let right = attributes[attributesId][getDevice].right
                                let bottom = attributes[attributesId][getDevice].bottom
                                let left = attributes[attributesId][getDevice].left
                                let maximum = Math.max(top, right, bottom, left)
                                setAttributes({[attributesId]: {...attributes[attributesId], [getDevice]: {
                                    ...attributes[attributesId][getDevice],
                                    isLinked : !attributes[attributesId][getDevice].isLinked,
                                    top: maximum,
                                    right: maximum,
                                    bottom: maximum,
                                    left: maximum
                                }}})
                            }}
                        >
                            <FaLink/>
                        </button>
                    </li>
                </ul>
            </div>
        </BaseControl>
    );
}