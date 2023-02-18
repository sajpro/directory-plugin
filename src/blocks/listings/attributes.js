import { AdvancedAttributes } from "../../utils/AdvancedControls"
import { generateTypographyAttributes, generateDimensionAttributes } from "../../utils/helper"

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
        type: "string",
        default: "12"
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
    secTitleNormalColor: {
        type: "string",
        default: ""
    },
    secTitleHoverColor: {
        type: "string",
        default: ""
    },
    secTitleTransition: {
        type: "string",
        default: ""
    },
    secSubtitleNormalColor: {
        type: "string",
        default: ""
    },
    secSubtitleHoverColor: {
        type: "string",
        default: ""
    },
    secSubtitleTransition: {
        type: "string",
        default: ""
    },
	...generateDimensionAttributes('secTitlePadding',{
		top: '',
		right: '',
		bottom: '',
		left: '',
		unit: '',
		isLinked: true,
	}),
	...generateDimensionAttributes('secSubtitlePadding',{
		top: '',
		right: '',
		bottom: '',
		left: '',
		unit: '',
		isLinked: true,
	}),
	...generateTypographyAttributes('secTitleTypography',{
		fontSize: {
			value: "",
			unit: "",
		},
		lineHeight: {
			value: "",
			unit: "",
		},
		letterSpacing: {
			value: "",
			unit: "",
		},
		wordSpacing: {
			value: "",
			unit: "",
		},
	}),
	...generateTypographyAttributes('secSubtitleTypography',{
		fontSize: {
			value: "",
			unit: "",
		},
		lineHeight: {
			value: "",
			unit: "",
		},
		letterSpacing: {
			value: "",
			unit: "",
		},
		wordSpacing: {
			value: "",
			unit: "",
		},
	}),
};

export default attributes;