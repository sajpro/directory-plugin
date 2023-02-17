import { registerBlockType } from "@wordpress/blocks";

export const dpRegisterBlockType = (metadata, newData) => {

    let metaData = {
        title      : metadata.title,
        description: metadata.description,
        category   : metadata.category,
        supports   : metadata.supports,
    };

    return registerBlockType(metadata.name, {
        ...metaData,
        ...newData
    });
}
