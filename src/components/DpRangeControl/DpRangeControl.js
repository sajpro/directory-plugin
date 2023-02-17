import { RangeControl, BaseControl,Button } from "@wordpress/components";

export const DpRangeControl = (props) => {
    let { attributes, setAttributes,attributesId, label, min, max, step } = props;

    const handleOnChange = (v) => {
        setAttributes( {[attributesId]: v} )
    }

    return (
        <BaseControl
            label={label}
        >
            <RangeControl
                value={ attributes[attributesId] }
                onChange={ (v) => handleOnChange(v) }
                min={ min || 1 }
                max={ max || 100 }
                step={ step || 1}
            />
        </BaseControl>
    );
}
