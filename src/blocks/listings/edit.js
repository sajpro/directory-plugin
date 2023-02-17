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

import { getBlockId, generateDimensionStyles, generateBgImageStyle, generateTypographyStyle, minifyCSS } from "../../utils/helper";

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
        secTitleNormalColor,
        secTitleHoverColor,
        wrapperCustomCss
    } = attributes;

    const serverAttr = {
        ...attributes
    }
console.log(serverAttr);
    // create a unique id for blocks
    useEffect(() => {
        const blockPrefix = "dpid";

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

    // wrapper background styles
    let {backgroundStyles:{
        Desktop: wrapperBgStylesDesktop,
        Tablet: wrapperBgStylesTablet,
        Mobile: wrapperBgStylesMobile,
        Transition: wrapperBgTransition
    }} = generateBgImageStyle({
		attributesIdType: 'wrapperBgType',
		attributesIdColor: 'wrapperBgColor',
		attributesIdImage: 'wrapperBgImage',
		attributesIdGradient: 'wrapperBgGradient',
		attributesIdTransition: 'wrapperBgTransition',
        attributes
    })

    // section title typography
    let {typoStyle:{
        Desktop: secTitleTypoStyleDesktop,
        Tablet: secTitleTypoStyleTablet,
        Mobile: secTitleTypoStyleMobile
    }} = generateTypographyStyle({
        attributesId: 'secTitleTypography',
        attributes
    })

    // Wrapper styles css for desktop
    const wrapperStylesDesktop = `
        ${(wrapperMarginDesktop || wrapperBgStylesDesktop) ? (`
            .dp-listings-wrapper.${blockId}{
                ${wrapperMarginDesktop}
                ${wrapperBgStylesDesktop}
            }
        `):''}
    `;

    // Title styles css desktop in strings ⬇
	const sectionTitleStylesDesktop = `
        ${(secTitleNormalColor || secTitleTypoStyleDesktop) ? (`
            .dp-listings-wrapper.${blockId} .sec-title {
                ${secTitleNormalColor ? (`color: ${secTitleNormalColor};`) : ''}
                ${secTitleTypoStyleDesktop}
            }
        `):''}
        ${(secTitleHoverColor) ? (`
            .dp-listings-wrapper.${blockId} .sec-title:hover {
                color: ${secTitleHoverColor};
            }
        `):''}
    `;

    // Wrapper styles css for Tablet
    const wrapperStylesTablet = `
        ${(wrapperMarginTablet || wrapperBgStylesTablet) ? (`
            .dp-listings-wrapper.${blockId}{
                ${wrapperMarginTablet}
                ${wrapperBgStylesTablet}
            }
        `):''}
    `;

    // Title styles css desktop in strings ⬇
	const sectionTitleStylesTablet = `
        ${(secTitleTypoStyleTablet) ? (`
            .dp-listings-wrapper.${blockId} .sec-title {
                ${secTitleTypoStyleDesktop}
            }
        `):''}
    `;


    // Wrapper styles css for mobile
    const wrapperStylesMobile = `
        ${(wrapperMarginMobile || wrapperBgStylesMobile) ? (`
            .dp-listings-wrapper.${blockId}{
                ${wrapperMarginMobile}
                ${wrapperBgStylesMobile}
            }
        `):''}
    `;

    // Title styles css desktop in strings ⬇
	const sectionTitleStylesMobile = `
        ${(secTitleTypoStyleMobile) ? (`
            .dp-listings-wrapper.${blockId} .sec-title {
                ${secTitleTypoStyleMobile}
            }
        `):''}
    `;

	// all css styles for desktop in strings
	const desktopAllStyles = `
        ${wrapperStylesDesktop}
        ${sectionTitleStylesDesktop}
    `;

	// all css styles for desktop in strings
	const tabletAllStyles = `
        ${wrapperStylesTablet}
        ${sectionTitleStylesTablet}
    `;

    // all css styles for desktop in strings
	const mobileAllStyles = `
        ${wrapperStylesMobile}
        ${sectionTitleStylesMobile}
    `;

	// Set All Style in "blockStyles" Attribute
	useEffect(() => {
		const stylesObject = {
			desktop: minifyCSS(desktopAllStyles),
			tablet: minifyCSS(tabletAllStyles),
			mobile: minifyCSS(mobileAllStyles),
		};
		if (JSON.stringify(blockStyles) != JSON.stringify(stylesObject)) {
			setAttributes({ blockStyles: minifyCSS(JSON.stringify(stylesObject)) });
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
                    /* custom css */
                    ${minifyCSS(wrapperCustomCss)}
                `}

                </style>

                <div className={`dp-listings-wrapper ${blockId}`}>
                    <RichText
                        tagName="h2"
                        className="sec-title"
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