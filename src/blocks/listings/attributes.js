import { generateDimensionAttributes } from "../../utils/helpers"

const attributes = {
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
	...generateDimensionAttributes('wrapperMargin',{
		top: '',
		right: '',
		bottom: '',
		left: '',
		isLinked: false,
	})
};

export default attributes;