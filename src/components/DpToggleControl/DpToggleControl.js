import { ToggleControl, BaseControl } from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export const DpToggleControl = (props) => {
    let {attributes,setAttributes,attrId,label} = props;

    return (
        <BaseControl
            label={label}
        >
            <ToggleControl
                checked={ attributes[attrId] }
                onChange={ () => setAttributes({[attrId]: !attributes[attrId] }) }
            />
        </BaseControl>
    )
}
