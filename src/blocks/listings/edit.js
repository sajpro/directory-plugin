import { __ } from "@wordpress/i18n";
import ServerSideRender from '@wordpress/server-side-render';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import {
	Disabled,
	PanelBody,
	TextControl,
} from '@wordpress/components';

import Loader from "./Loader";

const Edit = ({attributes, setAttributes}) => {

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
}

export default Edit