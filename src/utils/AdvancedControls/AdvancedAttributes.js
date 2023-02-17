import { generateDimensionAttributes, generateBgeImageAttr } from "../helper"

export const AdvancedAttributes = {
	blockId: {
		type: "string",
	},
	blockStyles: {
		type: "object",
	},
	...generateDimensionAttributes('wrapperMargin',{
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
        default: "#000"
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
        default: "#000"
	},
	wrapperHoverBgGradient: {
        type: "string",
        default: ""
	},
	wrapperBgTransition: {
        type: "string",
        default: ""
	},
	...generateBgeImageAttr('wrapperHoverBgImage',{
		position: "",
		repeat: "",
		size: "",
		custom_size: "",
		unit: "",
	}),
};
