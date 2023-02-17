import { Button, Dropdown, BaseControl, SelectControl, RangeControl } from "@wordpress/components";
import { pencil } from "@wordpress/icons";
import { useState, useEffect } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
// import {DpCssUnitTypo} from "../DpCssUnitTypo"
import DpResponsiveControls from "../DpResponsiveControls/DpResponsiveControls"
import { useGetDevice } from "../../utils/getDevice";
import { FiRotateCcw } from "react-icons/fi";
import fonts from "./fonts.json"
import Select from 'react-select';

export const DpTypography = (props) => {
    let {attributes,setAttributes,attributesId,label} = props;

    const [selectedFonts, setSelectedFonts] = useState({});

    const getDevice = useGetDevice();


    const handleTypo = (v,attrVal) => {
        let newUnit = {...attributes[attributesId], [getDevice]: {...attributes[attributesId][getDevice],[attrVal]:{...attributes[attributesId][getDevice][attrVal],value: v}} }
        setAttributes({[attributesId]: newUnit})
    }

    const handleSingleTypo = (v,attr) => {
        let newUnit = {...attributes[attributesId], [attr]: v  }
        setAttributes({[attributesId]: newUnit})
    }

    const formatedWeight = (v) => {
        let label;
        switch (v) {
            case '100':
                label= { label: '100 (Thin)', value: '100' }
                break;
        
            case '200':
                label= { label: '200 (Extra Light)', value: '200' }
                break;
        
            case '300':
                label= { label: '200 (Extra Light)', value: '200' }
                break;
        
            case '400':
                label= { label: '400 (Normal)', value: '400' }
                break;

            case '500':
                label= { label: '500 (Medium)', value: '500' }
                break;

            case '600':
                label= { label: '600 (Semi Bold)', value: '600' }
                break;
        
            case '700':
                label= { label: '700 (Bold)', value: '700' }
                break;
        
            case '800':
                label= { label: '800 (Extra Bold)', value: '800' }
                break;
        
            case '900':
                label= { label: '900 (Black)', value: '900' }
                break;
        
            default:
                label= { label: 'Default', value: '' }
                break;
        }
        return label;        
    }

    const fontWeightDropdown = (attributes,attributesId) => {
        let weights = fonts.filter(font => {
            if(font.label == attributes[attributesId]?.fontFamily?.value){
                return font.weight;
            }
        })
        if(weights.length){
            return [{label:'Default',value:''},...weights[0].weight.map(item=> formatedWeight(item))];
        }else{
            return [{label:'Default',value:''}]
        }
    }

    const fontFamilyHandle = (attributes,attributesId,v) => {

        setAttributes({[attributesId]: {...attributes[attributesId], fontFamily: v  }})

        if(['Arial','Helvetica','Times New Roman','Georgia'].includes(v.value)) return;

        // setSelectedFonts({...selectedFonts,[attributes.blockId]:v.value})

        const meta = wp.data.select("core/editor").getEditedPostAttribute("meta");
        let fm = '';
        let fmObj = {};
        const googleFontsAttr =
        ":100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic";
        const link = document.createElement("link");
        link.rel = "stylesheet";
        console.log(meta);
        if ( typeof meta !== "undefined" && typeof meta._fb_fonts_attr !== "undefined" && meta._fb_fonts_attr !== "" ) {
            fmObj = JSON.parse(meta._fb_fonts_attr)
        }

        link.href = "https://fonts.googleapis.com/css?family=" + v.value.replace(/ /g, "+") + googleFontsAttr;
        document.head.appendChild(link);

        fmObj = {...fmObj,[attributes.blockId]: v.value}

        wp.data.dispatch("core/editor").editPost({
            meta: {
                _fb_fonts_attr: JSON.stringify(fmObj)
            }
        });

    }

    return (
        <BaseControl
            label={label}
        >
            <Dropdown
                contentClassName="dp-base-popover-control"
                position="bottom left"
                renderToggle={ ( { isOpen, onToggle } ) => (
                    <Button
                        variant="primary"
                        onClick={ onToggle }
                        aria-expanded={ isOpen }
                        icon={pencil}
                    />
                ) }
                renderContent={ () => (
                    <div className="dp-base-popover">
                        <div className="dp-base-popover-heading">
                            <div className="title">
                                <h5>Typography</h5>
                            </div>
                            <div className="reset-btn">
                                <FiRotateCcw/>
                            </div>
                        </div>
                        <br/>
                        <div className="dp-base-popover-body">
                            <BaseControl
                                label="Font Family"
                            >
                                <Select
                                    className='dp-select-font-family'
                                    value={ attributes[attributesId].fontFamily }
                                    onChange={ v => fontFamilyHandle(attributes,attributesId,v) }
                                    options={fonts}
                                />
                                {/* <SelectControl
                                    value={ attributes[attributesId].fontFamily }
                                    options={fonts}
                                    onChange={ v => handleSingleTypo(v,"fontFamily") }
                                /> */}
                            </BaseControl>

                            <BaseControl
                                label="Size"
                                className={`dp-css-value-wrapper dp-not-flex`}
                            >
                                <DpResponsiveControls {...{attributes, setAttributes,attributesId}}/>
                                {/* <DpCssUnitTypo {...{attributes, setAttributes,attributesId, }}
                                attrUnit="fontSize"
                                attrUnitD="fontSizeD"
                                /> */}

                                <RangeControl
                                    value={ attributes[attributesId][getDevice].fontSize.value }
                                    onChange={ (v) => handleTypo(v,"fontSize") }
                                    min={ 0 }
                                    max={ 200 }
                                    step={ 1}
                                />
                            </BaseControl>

                            <BaseControl
                                label="Weight"
                            >
                                <SelectControl
                                    value={ attributes[attributesId].fontWeight }
                                    options={ fontWeightDropdown(attributes,attributesId) }
                                    onChange={ v => handleSingleTypo(v,"fontWeight") }
                                />
                            </BaseControl>

                            <BaseControl
                                label="Transform"
                            >
                                <SelectControl
                                    value={ attributes[attributesId].textTransform }
                                    options={[
                                        { label: 'Default', value: '' },
                                        { label: 'uppercase', value: 'uppercase' },
                                        { label: 'lowercase', value: 'lowercase' },
                                        { label: 'capitalize', value: 'capitalize' },
                                        { label: 'none', value: 'none' },
                                    ] }
                                    onChange={ v => handleSingleTypo(v,"textTransform") }
                                />
                            </BaseControl>

                            <BaseControl
                                label="Style"
                            >
                                <SelectControl
                                    value={ attributes[attributesId].fontStyle }
                                    options={[
                                        { label: 'Default', value: '' },
                                        { label: 'normal', value: 'normal' },
                                        { label: 'italic', value: 'italic' },
                                        { label: 'oblique', value: 'oblique' },
                                    ] }
                                    onChange={ v => handleSingleTypo(v,"fontStyle") }
                                />
                            </BaseControl>

                            <BaseControl
                                label="Decoration"
                            >
                                <SelectControl
                                    value={ attributes[attributesId].textDecoration }
                                    options={[
                                        { label: 'Default', value: '' },
                                        { label: 'underline', value: 'underline' },
                                        { label: 'overline', value: 'overline' },
                                        { label: 'line-through', value: 'line-through' },
                                        { label: 'underline overline', value: 'underline overline' },
                                        { label: 'none', value: 'none' },
                                    ] }
                                    onChange={ v => handleSingleTypo(v,"textDecoration") }
                                />
                            </BaseControl>

                            <BaseControl
                                label="Line Height"
                                className={`dp-css-value-wrapper dp-not-flex`}
                            >
                                <DpResponsiveControls {...{attributes, setAttributes,attributesId}}/>
                                {/* <DpCssUnitTypo {...{attributes, setAttributes,attributesId, }}
                                attrUnit="lineHeight"
                                attrUnitD="lineHeightD"
                                /> */}

                                <RangeControl
                                    value={ attributes[attributesId][getDevice].lineHeight.value }
                                    onChange={ (v) => handleTypo(v,"lineHeight") }
                                    min={ 0 }
                                    max={ 200 }
                                    step={ 1}
                                />
                            </BaseControl>

                            <BaseControl
                                label="Letter Spacing"
                                className={`dp-css-value-wrapper dp-not-flex`}
                            >
                                <DpResponsiveControls {...{attributes, setAttributes,attributesId}}/>
                                {/* <DpCssUnitTypo {...{attributes, setAttributes,attributesId, }}
                                attrUnit="letterSpacing"
                                attrUnitD="letterSpacingD"
                                /> */}

                                <RangeControl
                                    value={ attributes[attributesId][getDevice].letterSpacing.value }
                                    onChange={ (v) => handleTypo(v,"letterSpacing") }
                                    min={ 0 }
                                    max={ 200 }
                                    step={ 1}
                                />
                            </BaseControl>

                            <BaseControl
                                label="Word Spacing"
                                className={`dp-css-value-wrapper dp-not-flex`}
                            >
                                <DpResponsiveControls {...{attributes, setAttributes,attributesId}}/>
                                {/* <DpCssUnitTypo {...{attributes, setAttributes,attributesId, }}
                                attrUnit="wordSpacing"
                                attrUnitD="wordSpacingD"
                                /> */}

                                <RangeControl
                                    value={ attributes[attributesId][getDevice].wordSpacing.value }
                                    onChange={ (v) => handleTypo(v,"wordSpacing") }
                                    min={ 0 }
                                    max={ 200 }
                                    step={ 1}
                                />
                            </BaseControl>

                        </div>
                    </div>
                ) }
            />
        </BaseControl>
    )
}