import { BaseControl } from "@wordpress/components";
import { FaLink} from 'react-icons/fa';

import {DpUnits} from "../DpUnits"
import DpResponsiveControls from "../DpResponsiveControls/DpResponsiveControls"

export const DpSpacingControls = (props) => {
    let { attributes, setAttributes,attributesId, label } = props;

    const handleOnChange = (e) => {

        let key = e.target.name;
        let value = e.target.value;

    }

    return (
        <BaseControl
            label={label}
        >
            <div className="units-responsive">
                <DpResponsiveControls {...{attributes, setAttributes,attributesId}}/>
                <DpUnits {...{attributes, setAttributes,attributesId}}/>
            </div>

            <div className="dp-space-controls">
                <ul>
                    <li>
                        <input type="number" name="top" onChange={handleOnChange} value={''}/>
                        <span>Top</span>
                    </li>
                    <li>
                        <input type="number" name="right" onChange={handleOnChange} value={''} />
                        <span>Right</span>
                    </li>
                    <li>
                        <input type="number" name="bottom" onChange={handleOnChange} value={''}/>
                        <span>Bottom</span>
                    </li>
                    <li>
                        <input type="number" name="left" onChange={handleOnChange} value={''}/>
                        <span>Left</span>
                    </li>
                    <li>
                        <button
                            type="submit"
                            className={ `linked` }
                            onClick={ () => {
                                
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