import { __ } from "@wordpress/i18n";
import ServerSideRender from '@wordpress/server-side-render';
import { InspectorControls, RichText, useBlockProps } from '@wordpress/block-editor';
import {
	Disabled,
	PanelBody,
	TextControl,
} from '@wordpress/components';

import Loader from "./Loader";

const Edit = (props) => {
    let {attributes,setAttributes,className,clientId} = props;
    let {
        title, 
        subtitle
    } = attributes;

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
                <TextControl
                    label={ __( 'Subitle', 'directory-plugin' ) }
                    value={attributes.subtitle}
                    onChange={ (v) =>
                        setAttributes( { subtitle: v } )
                    }
                />
            </PanelBody>
        </InspectorControls>
            <RichText
                tagName="h2"
                className="dp-sec-title"
                style={{textAlign:"center"}}
                onChange={(v) => setAttributes({ title: v })}
                value={title}
            />
            <RichText
                tagName="p"
                className="dp-sec-subtitle"
                style={{textAlign:"center"}}
                onChange={(v) => setAttributes({ subtitle: v })}
                value={subtitle}
            />
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