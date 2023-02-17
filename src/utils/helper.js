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