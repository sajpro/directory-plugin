import { __ } from "@wordpress/i18n";
import ServerSideRender from '@wordpress/server-side-render';
import { useEffect } from "@wordpress/element";
import classnames from "classnames";
import { useBlockProps } from '@wordpress/block-editor';
import {
	Disabled
} from '@wordpress/components';
import Inspector from "./inspector";

import Loader from "./Loader";

import { getBlockId, generateDimensionStyles, generateBgImageStyle, generateTypographyStyle, generateTransitonStyle, minifyCSS } from "../../utils/helper";

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
        secSubtitleNormalColor,
        secSubtitleHoverColor,
        wrapperCustomCss,
        wrapperBgTransition,
        secTitleTransition,
        secSubtitleTransition,
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

    // wrapper padding
    let {dimensionStyle:{
        Desktop: wrapperPaddingDesktop,
        Tablet: wrapperPaddingTablet,
        Mobile: wrapperPaddingMobile
    }} = generateDimensionStyles({
		attributesId: 'wrapperPadding',
		styleFor: "padding",
        attributes
    })

    // wrapper background styles
    let {backgroundStyles:{
        Desktop: wrapperBgStylesDesktop,
        Tablet: wrapperBgStylesTablet,
        Mobile: wrapperBgStylesMobile
    }} = generateBgImageStyle({
		attributesIdType: 'wrapperBgType',
		attributesIdColor: 'wrapperBgColor',
		attributesIdImage: 'wrapperBgImage',
		attributesIdGradient: 'wrapperBgGradient',
        attributes
    })

    // wrapper background styles hover
    let {backgroundStyles:{
        Desktop: wrapperHoverBgStylesDesktop,
        Tablet: wrapperHoverBgStylesTablet,
        Mobile: wrapperHoverBgStylesMobile
    }} = generateBgImageStyle({
        attributesIdType: 'wrapperHoverBgType',
        attributesIdColor: 'wrapperHoverBgColor',
        attributesIdImage: 'wrapperHoverBgImage',
        attributesIdGradient: 'wrapperHoverBgGradient',
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

    // section title padding
    let {dimensionStyle:{
        Desktop: secTitlePaddingDesktop,
        Tablet: secTitlePaddingTablet,
        Mobile: secTitlePaddingMobile
    }} = generateDimensionStyles({
		attributesId: 'secTitlePadding',
		styleFor: "padding",
        attributes
    })

    // section subtitle typography
    let {typoStyle:{
        Desktop: secSubtitleTypoStyleDesktop,
        Tablet: secSubtitleTypoStyleTablet,
        Mobile: secSubtitleTypoStyleMobile
    }} = generateTypographyStyle({
        attributesId: 'secSubtitleTypography',
        attributes
    })

    // section subtitle padding
    let {dimensionStyle:{
        Desktop: secSubtitlePaddingDesktop,
        Tablet: secSubtitlePaddingTablet,
        Mobile: secSubtitlePaddingMobile
    }} = generateDimensionStyles({
		attributesId: 'secSubtitlePadding',
		styleFor: "padding",
        attributes
    })

    // section title hover transition
    let {transitoins: secTitleTransitonValue} = generateTransitonStyle({
        attributesId: 'secTitleTransition',
        attributes
    })

    // section subtitle hover transition
    let {transitoins: secSubtitleTransitonValue} = generateTransitonStyle({
        attributesId: 'secSubtitleTransition',
        attributes
    })

    // wrapper background hover transition
    let {transitoins: wrapperBgTransitonValue} = generateTransitonStyle({
        attributesId: 'wrapperBgTransition',
        attributes,
        type: 'background'
    })
    
    // Wrapper styles css for desktop
    const wrapperStylesDesktop = `
        ${(wrapperMarginDesktop || wrapperBgStylesDesktop || wrapperPaddingDesktop) ? (`
            .dp-listings-wrapper.${blockId}{
                ${wrapperMarginDesktop}
                ${wrapperPaddingDesktop}
                ${wrapperBgStylesDesktop}
                ${wrapperBgTransition ? `transition: ${wrapperBgTransitonValue.join(", ")};` : ''}
                
            }
        `):''}
        ${(wrapperHoverBgStylesDesktop) ? (`
            .dp-listings-wrapper.${blockId}:hover{
                ${wrapperHoverBgStylesDesktop}
            }
        `):''}
    `;

    // Title styles css desktop in strings ⬇
	const sectionTitleStylesDesktop = `
        ${(secTitleNormalColor || secTitleTypoStyleDesktop || secTitleTransition || secTitlePaddingDesktop) ? (`
            .dp-listings-wrapper.${blockId} .sec-title {
                ${secTitleNormalColor ? (`color: ${secTitleNormalColor};`) : ''}
                ${secTitlePaddingDesktop}
                ${secTitleTypoStyleDesktop}
                ${secTitleTransition ? `transition: ${secTitleTransitonValue.join(", ")};` : ''}
            }
        `):''}
        ${(secTitleHoverColor) ? (`
            .dp-listings-wrapper.${blockId} .sec-title:hover {
                color: ${secTitleHoverColor};
            }
        `):''}
    `;

    // Subtitle styles css desktop in strings ⬇
	const sectionSubtitleStylesDesktop = `
        ${(secSubtitleNormalColor || secSubtitleTypoStyleDesktop || secSubtitleTransition || secSubtitlePaddingDesktop) ? (`
            .dp-listings-wrapper.${blockId} .sec-sub-title {
                ${secSubtitleNormalColor ? (`color: ${secSubtitleNormalColor};`) : ''}
                ${secSubtitlePaddingDesktop}
                ${secSubtitleTypoStyleDesktop}
                transition: ${secSubtitleTransitonValue.join(", ")};
                ${secSubtitleTransition ? `transition: ${secSubtitleTransitonValue.join(", ")};` : ''}
            }
        `):''}
        ${(secSubtitleHoverColor) ? (`
            .dp-listings-wrapper.${blockId} .sec-sub-title:hover {
                color: ${secSubtitleHoverColor};
            }
        `):''}
    `;

    // Wrapper styles css for Tablet
    const wrapperStylesTablet = `
        ${(wrapperMarginTablet || wrapperBgStylesTablet || wrapperPaddingTablet || secTitlePaddingTablet) ? (`
            .dp-listings-wrapper.${blockId}{
                ${wrapperMarginTablet}
                ${wrapperPaddingTablet}
                ${secTitlePaddingTablet}
                ${wrapperBgStylesTablet}
            }
        `):''}
        ${(wrapperHoverBgStylesTablet) ? (`
            .dp-listings-wrapper.${blockId}:hover{
                ${wrapperHoverBgStylesTablet}
            }
        `):''}
    `;

    // Title styles css tablet in strings ⬇
	const sectionTitleStylesTablet = `
        ${(secTitleTypoStyleTablet ) ? (`
            .dp-listings-wrapper.${blockId} .sec-title {
                ${secTitleTypoStyleTablet}
            }
        `):''}
    `;

    // Subtitle styles css tablet in strings ⬇
	const sectionSubtitleStylesTablet = `
        ${(secSubtitleTypoStyleTablet || secSubtitlePaddingTablet) ? (`
            .dp-listings-wrapper.${blockId} .sec-sub-title {
                ${secSubtitleTypoStyleTablet}
                ${secSubtitlePaddingTablet}
            }
        `):''}
    `;

    // Wrapper styles css for mobile
    const wrapperStylesMobile = `
        ${(wrapperMarginMobile || wrapperBgStylesMobile || wrapperPaddingMobile || secTitlePaddingMobile) ? (`
            .dp-listings-wrapper.${blockId}{
                ${wrapperMarginMobile}
                ${wrapperPaddingMobile}
                ${secTitlePaddingMobile}
                ${wrapperBgStylesMobile}
            }
        `):''}
        ${(wrapperHoverBgStylesMobile) ? (`
            .dp-listings-wrapper.${blockId}:hover{
                ${wrapperHoverBgStylesMobile}
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

    // Subtitle styles css desktop in strings ⬇
	const sectionSubtitleStylesMobile = `
        ${(secSubtitleTypoStyleMobile || secSubtitlePaddingMobile) ? (`
            .dp-listings-wrapper.${blockId} .sec-sub-title {
                ${secSubtitleTypoStyleMobile}
                ${secSubtitlePaddingMobile}
            }
        `):''}
    `;

	// all css styles for desktop in strings
	const desktopAllStyles = `
        ${wrapperStylesDesktop}
        ${sectionTitleStylesDesktop}
        ${sectionSubtitleStylesDesktop}
    `;

	// all css styles for desktop in strings
	const tabletAllStyles = `
        ${wrapperStylesTablet}
        ${sectionTitleStylesTablet}
        ${sectionSubtitleStylesTablet}
    `;

    // all css styles for desktop in strings
	const mobileAllStyles = `
        ${wrapperStylesMobile}
        ${sectionTitleStylesMobile}
        ${sectionSubtitleStylesMobile}
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
console.log(blockProps);
    return (
        <>
            <Inspector {...{attributes,setAttributes}}/>

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

            <Disabled>
                <ServerSideRender
                    LoadingResponsePlaceholder={Loader}
                    block="directory-plugin/listings"
                    attributes={ serverAttr }
                    httpMethod="POST"
                />
            </Disabled>
        </>
    );
}

export default Edit