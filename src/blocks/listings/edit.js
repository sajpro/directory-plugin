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

import { getBlockId, generateDimensionStyles, minifyCSS } from "../../utils/helper";

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

    // blocks prop wrapper div
    const blockProps = useBlockProps({
		className: classnames(className, `dp-listings-block`),
	});

    // wrapper margin
    let {dimensionStyle:{
        Desktop: wrapperMarginDesktop,
        Tablet: wrapperMarginTablet,
        Mobile: wrapperMarginMobile
    }} = generateDimensionStyles({
		attributesId: 'wrapperMargin',
		styleFor: "margin",
        attributes
    })

    // Wrapper styles css for desktop
    const wrapperStylesDesktop = `
        ${wrapperMarginDesktop ? (`
            .dp-listings-wrapper.${blockId}{
                ${wrapperMarginDesktop}
            }
        `):''}
    `;

    // Wrapper styles css for Tablet
    const wrapperStylesTablet = `
        ${wrapperMarginTablet ? (`
            .dp-listings-wrapper.${blockId}{
                ${wrapperMarginTablet}
            }
        `):''}
    `;

    // Wrapper styles css for mobile
    const wrapperStylesMobile = `
        ${wrapperMarginMobile ? (`
            .dp-listings-wrapper.${blockId}{
                ${wrapperMarginMobile}
            }
        `):''}
    `;

	// all css styles for desktop in strings
	const desktopAllStyles = `
        ${wrapperStylesDesktop}
    `;

	// all css styles for desktop in strings
	const tabletAllStyles = `
        ${wrapperStylesTablet}
    `;

    // all css styles for desktop in strings
	const mobileAllStyles = `
        ${wrapperStylesMobile}
    `;

	// Set All Style in "blockStyles" Attribute
	useEffect(() => {
		const stylesObject = {
			desktop: desktopAllStyles,
			tablet: tabletAllStyles,
			mobile: mobileAllStyles,
		};
		if (JSON.stringify(blockStyles) != JSON.stringify(stylesObject)) {
			setAttributes({ blockStyles: JSON.stringify(stylesObject) });
		}
	}, [attributes]);

    return (
        <>
            <Inspector {...{attributes,setAttributes}}/>

            <div { ...blockProps }>

                <style>
                {`
                    /* Desktop styles Start */
                    ${minifyCSS(desktopAllStyles)}
                    /* Desktop styles End */

                    @media all and (max-width: 1024px) {
                        /* tablet styles Start */
                        ${minifyCSS(tabletAllStyles)}
                        /* tablet styles End */
                    }

                    @media all and (max-width: 767px) {
                        /* mobile styles Start */
                        ${minifyCSS(mobileAllStyles)}
                        /* mobile styles End */
                    }
                `}

                </style>

                <div className={`dp-listings-wrapper ${blockId}`}>
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
            </div>
        </>
    );
}

export default Edit