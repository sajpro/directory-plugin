import { __ } from "@wordpress/i18n";
import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
} from '@wordpress/components';

const Inspector = (props) => {
    let {attributes,setAttributes} = props;

    let {
        title, 
        subtitle
    } = attributes;

    return (
        <InspectorControls>
            <PanelBody title={ __( 'Advanced', 'directory-plugin' ) }>
                <TextControl
                    label={ __( 'Title', 'directory-plugin' ) }
                    value={title}
                    onChange={ (v) =>
                        setAttributes( { title: v } )
                    }
                />
                <TextControl
                    label={ __( 'Subitle', 'directory-plugin' ) }
                    value={subtitle}
                    onChange={ (v) =>
                        setAttributes( { subtitle: v } )
                    }
                />
            </PanelBody>
        </InspectorControls>
    )
}

export default Inspector