import { RangeControl, BaseControl,Button } from "@wordpress/components";

export const DpRangeControl = (props) => {
    let { attributes, setAttributes,attrId, label, min, max, step } = props;

    const handleOnChange = (v) => {
        setAttributes( {[attrId]: v} )
    }

    return (
        <BaseControl
            label={label}
        >
            <RangeControl
                value={ attributes[attrId] }
                onChange={ (v) => handleOnChange(v) }
                min={ min || 1 }
                max={ max || 100 }
                step={ step || 1}
            />
        </BaseControl>
    );
}
