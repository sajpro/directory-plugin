import { select } from '@wordpress/data';
import { v1 as uuidv1 } from 'uuid';

// check if block id exists
const blockIdExist = ( blockId, clientId ) => {
    const blocksClientIds = select( 'core/block-editor' ).getClientIdsWithDescendants();
    return blocksClientIds.some( ( _clientId ) => {
        const { blockId: _blockId } = select( 'core/block-editor' ).getBlockAttributes( _clientId );
        return clientId !== _clientId && blockId === _blockId;
    } );
};

const getUniqueId = (blockPrefix,setAttributes,clientId) => {
    const uid = uuidv1().split("-").pop().match(/.{1,3}/g).pop();
    const cid = clientId.split("-").pop().match(/.{1,3}/g).pop();
    setAttributes({ blockId: `${blockPrefix}-${uid}${cid}` });
}

// generate unique block id
export const getBlockId = ({blockPrefix,blockId,setAttributes,clientId}) => {

    if ( ! blockId ) {
        getUniqueId(blockPrefix,setAttributes,clientId)
    }

    if ( blockIdExist( blockId, clientId ) ) {
        getUniqueId(blockPrefix,setAttributes,clientId)
    }
}

// generate minify css code
export const minifyCSS = (css) => {
    let css_string = css.split("\n").join("").trim().replace(/\s+/g, " ")
    // let css_string = css.split("\n").join("").trim().replace(/[\r\n\s]/gm, '');
    // .replace(/\s+(?=[^{\}]*\})/g, "")
    return css_string;
};

export const generateDimensionAttributes = (attributesId,attributesObject) => {
    return {
        [attributesId]:  {
            type: "object",
            default: {
                unitDefault: "px",
                Desktop: attributesObject,
                Tablet: attributesObject,
                Mobile: attributesObject
            }
        }
    }
}

export const generateTypographyAttributes = (attributesId,attributesObject) => {
    return {
        [attributesId]:  {
            type: "object",
            default: {
                fontFamily: "",
                fontWeight: "",
                textTransform: "",
                fontStyle: "",
                textDecoration: "",
                Desktop: attributesObject,
                Tablet: attributesObject,
                Mobile: attributesObject,
                fontSizeDefaultUnit: "px",
                lineHeightDefaultUnit: "px",
                letterSpacingDefaultUnit: "px",
                wordSpacingDefaultUnit: "px",
            }
        }
    }
}


export const generateBgeImageAttr = (attributesId,attributesObject) => {
    return {
        [attributesId]:  {
            type: "object",
            default: {
                id: "",
                url: "",
                attachment: "",
                Desktop: attributesObject,
                Tablet: attributesObject,
                Mobile: attributesObject,
                unitDefault: "px",
            }
        }
    }
}

export const generateDimensionStyles = ({attributesId,styleFor,attributes}) => {
    let unit_default = attributes[attributesId].unitDefault

    let devices = [
        'Desktop',
        'Tablet',
        'Mobile'
    ]

    let dimensionStyle={}
    
    devices.forEach(device=>{
        let top = (attributes[attributesId][device].top ? `${styleFor}-top:${attributes[attributesId][device].top}${attributes[attributesId][device].unit || unit_default};` : '')
        let right = (attributes[attributesId][device].right ? `${styleFor}-right:${attributes[attributesId][device].right}${attributes[attributesId][device].unit || unit_default};` : '')
        let bottom = (attributes[attributesId][device].bottom ? `${styleFor}-bottom:${attributes[attributesId][device].bottom}${attributes[attributesId][device].unit || unit_default};` : '')
        let left = (attributes[attributesId][device].left ? `${styleFor}-left:${attributes[attributesId][device].left}${attributes[attributesId][device].unit || unit_default};` : '')
        let dimensionStyles = `${top} ${right} ${bottom} ${left}`
        dimensionStyle[device] = dimensionStyles.trim() || '';
    })

    return {dimensionStyle};
}

export const generateBgImageStyle = ({attributesIdType, attributesIdColor, attributesIdImage, attributesIdGradient, attributesIdTransition, attributes}) => {
    let unit_default = attributes[attributesIdImage].unitDefault

    let devices = [
        'Desktop',
        'Tablet',
        'Mobile'
    ]

    let backgroundStyles={}
    
    if(attributes[attributesIdType] == 'classic'){
        let bgColor = ((attributes[attributesIdColor]) ? `background-color: ${attributes[attributesIdColor]};` : '')
        let bgImage = (attributes[attributesIdImage].url ? `background-image: url(${attributes[attributesIdImage].url});` : '')
        let attachment = (attributes[attributesIdImage].attachment ? `background-attachment: ${attributes[attributesIdImage].attachment};` : '')
        devices.forEach(device=>{
            let position = (attributes[attributesIdImage][device].position ? `background-position: ${attributes[attributesIdImage][device].position};` : '')
            let repeat = (attributes[attributesIdImage][device].repeat ? `background-repeat: ${attributes[attributesIdImage][device].repeat};` : '')
            let size = (((attributes[attributesIdImage][device].size) && (attributes[attributesIdImage][device].size != 'custom' ))? `background-size: ${attributes[attributesIdImage][device].size};` : (attributes[attributesIdImage][device].custom_size ? `background-size: ${attributes[attributesIdImage][device].custom_size}${attributes[attributesIdImage][device].unit || unit_default };` : ''))
            let backgroundStyless = `${position} ${repeat} ${size}`
            backgroundStyles[device] = backgroundStyless.trim() || '';
        })

        backgroundStyles['Transition'] = (attributes[attributesIdTransition] ? `background ${attributes[attributesIdTransition]}s` : '')
        let backgroundDesktopOnly = `${bgColor} ${bgImage} ${attachment}`
        backgroundStyles['Desktop'] = (backgroundDesktopOnly.trim() || '') + backgroundStyles['Desktop']
    }else{
        backgroundStyles['Desktop'] = (attributes[attributesIdGradient] ? `background: ${attributes[attributesIdGradient]};` : '')
    }

    return {backgroundStyles};
}


export const generateTypographyStyle = ({attributesId,attributes}) => {
    let sizeUnitD = attributes[attributesId].fontSizeDefaultUnit
    let lhUnitD   = attributes[attributesId].lineHeightDefaultUnit
    let lsUnitD   = attributes[attributesId].letterSpacingDefaultUnit
    let wsUnitD   = attributes[attributesId].wordSpacingDefaultUnit

    let devices = [
        'Desktop',
        'Tablet',
        'Mobile'
    ]

    let typoStyle={}

    let font_family     = (attributes[attributesId].fontFamily ? `font-family:${attributes[attributesId].fontFamily.value};` : ``)
    let font_weight     = (attributes[attributesId].fontWeight ? `font-weight:${attributes[attributesId].fontWeight};` : ``)
    let text_transform  = (attributes[attributesId].textTransform ? `text-transform:${attributes[attributesId].textTransform};` : ``)
    let font_style      = (attributes[attributesId].fontStyle ? `font-style:${attributes[attributesId].fontStyle};` : ``)
    let text_decoration = (attributes[attributesId].textDecoration ? `text-decoration:${attributes[attributesId].textDecoration};` : ``)

    devices.forEach(device=>{
        let font_size = (attributes[attributesId][device].fontSize.value ? `font-size:${attributes[attributesId][device].fontSize.value}${attributes[attributesId][device].fontSize.unit || sizeUnitD };` : '')
        let line_height = (attributes[attributesId][device].lineHeight.value ? `line-height:${attributes[attributesId][device].lineHeight.value}${attributes[attributesId][device].lineHeight.unit || lhUnitD };` : '')
        let letter_spacing = (attributes[attributesId][device].letterSpacing.value ? `letter-spacing:${attributes[attributesId][device].letterSpacing.value}${attributes[attributesId][device].letterSpacing.unit || lsUnitD };` : '')
        let word_spacing = (attributes[attributesId][device].wordSpacing.value ? `word-spacing:${attributes[attributesId][device].wordSpacing.value}${attributes[attributesId][device].wordSpacing.unit || wsUnitD };` : '')
        let typoStylelean = `${font_size} ${line_height} ${letter_spacing} ${word_spacing} `
        typoStyle[device] = typoStylelean.trim() || '';
    })

    let typoDesktopOnly = `${font_family} ${font_weight} ${text_transform} ${font_style} ${text_decoration}`
    typoStyle['Desktop'] = (typoDesktopOnly.trim() || '') + typoStyle['Desktop']

    return {typoStyle};
}