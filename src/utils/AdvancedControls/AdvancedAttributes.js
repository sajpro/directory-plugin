import { generateDimensionAttributes, generateBgeImageAttr } from "../helper"

export const AdvancedAttributes = {
	blockId: {
		type: "string",
	},
	blockStyles: {
		type: "string",
        default: ""
	},
	...generateDimensionAttributes('wrapperMargin',{
		top: '',
		right: '',
		bottom: '',
		left: '',
		unit: '',
		isLinked: true,
	}),
	...generateDimensionAttributes('wrapperPadding',{
		top: '',
		right: '',
		bottom: '',
		left: '',
		unit: '',
		isLinked: true,
	}),
	wrapperBgType: {
        type: "string",
        default: "classic"
	},
	wrapperBgColor: {
        type: "string",
        default: ""
	},
	wrapperBgGradient: {
        type: "string",
        default: ""
	},
	...generateBgeImageAttr('wrapperBgImage',{
		position: "",
		repeat: "",
		size: "",
		custom_size: "",
		unit: "",
	}),
	wrapperHoverBgType: {
        type: "string",
        default: "classic"
	},
	wrapperHoverBgColor: {
        type: "string",
        default: ""
	},
	wrapperHoverBgGradient: {
        type: "string",
        default: ""
	},
	wrapperBgTransition: {
        type: "string",
        default: ""
	},
	wrapperCustomCss: {
        type: "string",
        default: `/* Write your CSS here */ \n.dp-listing-wrap{\n\tmargin:0;\n}`
	},
	...generateBgeImageAttr('wrapperHoverBgImage',{
		position: "",
		repeat: "",
		size: "",
		custom_size: "",
		unit: "",
	}),
};
