import { __ } from "@wordpress/i18n";
import ServerSideRender from '@wordpress/server-side-render';
import { useEffect } from "@wordpress/element";
import classnames from "classnames";
import { RichText, useBlockProps } from '@wordpress/block-editor';
import {
	Disabled
} from '@wordpress/components';
import Inspector from "./inspector";

import Loader from "./Loader";

import {getBlockId} from "../../utils/helper";

const Edit = (props) => {
    let {attributes,setAttributes,className,clientId} = props;
    let {
        blockId,
        blockStyles,
        title, 
        subtitle,
        number,
        showPagination,
        showSubmitButton,
    } = attributes;

    const serverAttr = {
        ...attributes
    }
    console.log(attributes);

    // blocks prop wrapper div
    const blockProps = useBlockProps({
		className: classnames(className, `dp-listings-wrapper`),
	});

    // create a unique id for blocks
    useEffect(() => {
        const blockPrefix = "dp-block";

        getBlockId({
            blockPrefix,
            blockId,
            setAttributes,
            clientId,
        });

	}, [ blockId ]);

    return (
        <div { ...blockProps }>
            
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