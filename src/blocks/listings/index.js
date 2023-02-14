import { __ } from "@wordpress/i18n";
import ServerSideRender from '@wordpress/server-side-render';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import {
	Disabled,
	PanelBody,
	TextControl,
} from '@wordpress/components';

import {dpRegisterBlockType} from "../../utils/register-blocks";
import metadata from '../../../blocks/listings/block.json';
// import attributes from "./attributes";
import Loader from "./Loader";

dpRegisterBlockType(metadata, {
    icon: 'book-alt',
    
    attributes: metadata.attributes,

    edit: ({attributes, setAttributes}) => {

        const serverAttr = {
            ...attributes
        }

        return (
            <div { ...useBlockProps() }>
			<InspectorControls>
				<PanelBody title={ __( 'Advanced', 'directory-plugin' ) }>
                    <TextControl
                        label={ __( 'Title', 'directory-plugin' ) }
                        value={attributes.title}
						onChange={ (v) =>
							setAttributes( { title: v } )
						}
                    />
				</PanelBody>
			</InspectorControls>
                <Disabled>
                    <ServerSideRender
                        LoadingResponsePlaceholder={Loader}
                        block="directory-plugin/listings"
                        attributes={ serverAttr }
                    />
                </Disabled>
            </div>
        );
    },
    save: (props) => {
        return null;
    }
});