import { BaseControl, GradientPicker } from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export const DpGradientPicker = (props) => {
    let {attributes,setAttributes,attributesId,label} = props;
    return (
        <BaseControl
            label={label}
            className={ 'dp-gradient-picker dp-not-flex dp-mb-8' }
        >
            <GradientPicker
                __nextHasNoMargin
                value={ attributes[attributesId] }
                onChange={ ( v ) => setAttributes({ [attributesId]: v }) }
                gradients={ [
                    {
                        name: 'JShine',
                        gradient:
                            'linear-gradient(135deg,#12c2e9 0%,#c471ed 50%,#f64f59 100%)',
                        slug: 'jshine',
                    },
                    {
                        name: 'Moonlit Asteroid',
                        gradient:
                            'linear-gradient(135deg,#0F2027 0%, #203A43 0%, #2c5364 100%)',
                        slug: 'moonlit-asteroid',
                    },
                    {
                        name: 'Rastafarie',
                        gradient:
                            'linear-gradient(135deg,#1E9600 0%, #FFF200 0%, #FF0000 100%)',
                        slug: 'rastafari',
                    },
                ] }
            />
        </BaseControl>
    );
}