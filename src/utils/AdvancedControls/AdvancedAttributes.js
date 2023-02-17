import { generateDimensionAttributes } from "../helper"

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
	})
};
