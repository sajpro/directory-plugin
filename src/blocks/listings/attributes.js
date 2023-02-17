import { AdvancedAttributes } from "../../utils/AdvancedControls"
// import { generateDimensionAttributes } from "../../utils/helper"

const attributes = {
    ...AdvancedAttributes,
    title: {
        type: "string",
        default: "This is Title"
    },
    subtitle: {
        type: "string",
        default: "This is Subtitle"
    },
    number: {
        type: "integer",
        default: 12
    },
    showPagination: {
        type: "boolean",
        default: true
    },
    showSubmitButton: {
        type: "boolean",
        default: true
    },
    align: {
        type: "string",
        default: "wide"
    },
};

export default attributes;