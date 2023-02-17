import { generateDimensionAttributes } from "../helper"

export const AdvancedAttributes = {
	...generateDimensionAttributes('wrapperMargin',{
		top: '',
		right: '',
		bottom: '',
		left: '',
		unit: '',
		isLinked: true,
	})
};
