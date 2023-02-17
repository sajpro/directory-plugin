import { __ } from "@wordpress/i18n";
import ServerSideRender from '@wordpress/server-side-render';
import { RichText, useBlockProps } from '@wordpress/block-editor';
import {
	Disabled
} from '@wordpress/components';
import Inspector from "./inspector";

import Loader from "./Loader";

const Edit = (props) => {
    let {attributes,setAttributes,className,clientId} = props;
    let {
        title, 
        subtitle,
        number,
        showPagination,
        showSubmitButton,
    } = attributes;

    const serverAttr = {
        ...attributes
    }

    return (
        <div { ...useBlockProps() }>
            
            <Inspector {...{attributes,setAttributes}}/>

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