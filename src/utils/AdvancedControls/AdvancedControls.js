import { __ } from "@wordpress/i18n";
import {
	PanelBody
} from '@wordpress/components';
import { DpSpacingControls } from "../../components/DpSpacingControls";

export const AdvancedControls = (props) => {
    let {attributes,setAttributes} = props;

    return (
        <>
            <PanelBody
            title={__('Layout','directory-plugin')}
            initialOpen={true}
            >
                <DpSpacingControls {...{attributes, setAttributes}}
                    label={__('Margin','directory-plugin')}
                    attributesId = 'wrapperMargin'
                />
            </PanelBody>
        </>
    )
}
