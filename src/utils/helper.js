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
    const uid = uuidv1().split("-").pop();
    const cid = clientId.split("-").pop();
    setAttributes({ blockId: `${blockPrefix}-${uid}-${cid}` });
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