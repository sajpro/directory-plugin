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