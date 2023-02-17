import { ToggleControl, BaseControl } from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export const DpToggleControl = (props) => {
    let {attributes,setAttributes,attributesId,label} = props;

    return (
        <BaseControl
            label={label}
        >
            <ToggleControl
                checked={ attributes[attributesId] }
                onChange={ () => setAttributes({[attributesId]: !attributes[attributesId] }) }
            />
        </BaseControl>
    )
}
