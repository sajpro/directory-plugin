import { __ } from "@wordpress/i18n";
import {
	PanelBody,
    TabPanel
} from '@wordpress/components';

import { DpSpacingControls } from "../../components/DpSpacingControls";
import { DpButtonGroup } from "../../components/DpButtonGroup";
import { DpMediaUpload } from "../../components/DpMediaUpload";
import { DpColorPalette } from "../../components/DpColorPalette";
import { DpGradientPicker } from "../../components/DpGradientPicker";
import { DpRangeControl } from "../../components/DpRangeControl";
import { DpCustomCss } from "../../components/DpCustomCss";

export const AdvancedControls = (props) => {
    let {attributes,setAttributes} = props;

    return (
        <>
            <PanelBody
            title={__('Layout','directory-plugin')}
            initialOpen={true}
            >
                <DpSpacingControls {...{attributes, setAttributes}}
                    label={__('Margin','directory-plugin')}
                    attributesId = 'wrapperMargin'
                />
            </PanelBody>

            <PanelBody
                title={__('Background','directory-plugin')}
                initialOpen={false}
                className="dp-style-tab"
            >
                <TabPanel
                    className='sec-tab-panel'
                    activeClass='active-tab'
                    tabs={[
                        {
                            name: 'normal',
                            title: 'Normal',
                        },
                        {
                            name: 'hover',
                            title: 'Hover',
                        }
                    ]}
                >
                    {(tab)=> <>
                        {(tab.name == 'normal') && (
                            <>
                                <DpButtonGroup {...{attributes, setAttributes}}
                                    label={__('Background Type','directory-plugin')}
                                    attributesId = 'wrapperBgType'
                                    buttons = {[
                                        { label: 'Classic', value: 'classic', icon: '' },
                                        { label: 'Gradient', value: 'gradient', icon: '' }
                                    ]}
                                />
                                {attributes.wrapperBgType == 'classic' && (
                                    <>
                                        <DpColorPalette {...{attributes, setAttributes}}
                                            label={__('Color','directory-plugin')}
                                            attributesId = 'wrapperBgColor'
                                        />
                                        <DpMediaUpload {...{attributes, setAttributes}}
                                            label={__('Image','directory-plugin')}
                                            attributesId = 'wrapperBgImage'
                                        />
                                    </>
                                )}
                                {attributes.wrapperBgType == 'gradient' && (
                                    <>
                                        <DpGradientPicker {...{attributes, setAttributes}}
                                            label={__('Gradient olor','directory-plugin')}
                                            attributesId = 'wrapperBgGradient'
                                        />
                                    </>
                                )}
                            </>
                        )}
                        {(tab.name == 'hover') && (
                            <>
                                <DpButtonGroup {...{attributes, setAttributes}}
                                    label={__('Background Type','directory-plugin')}
                                    attributesId = 'wrapperHoverBgType'
                                    buttons = {[
                                        { label: 'Classic', value: 'classic', icon: '' },
                                        { label: 'Gradient', value: 'gradient', icon: '' }
                                    ]}
                                />
                                {attributes.wrapperHoverBgType == 'classic' && (
                                    <>
                                        <DpColorPalette {...{attributes, setAttributes}}
                                            label={__('Color','directory-plugin')}
                                            attributesId = 'wrapperHoverBgColor'
                                        />
                                        <DpMediaUpload {...{attributes, setAttributes}}
                                            label={__('Image','directory-plugin')}
                                            attributesId = 'wrapperHoverBgImage'
                                        />
                                    </>
                                )}
                                {attributes.wrapperHoverBackgroundType == 'gradient' && (
                                    <>
                                        <DpGradientPicker {...{attributes, setAttributes}}
                                            label={__('Gradient Color','directory-plugin')}
                                            attributesId = 'wrapperHoverBgGradient'
                                        />
                                    </>
                                )}
                                <DpRangeControl {...{attributes, setAttributes}}
                                    label={__('Transition Duration','directory-plugin')}
                                    attributesId = 'wrapperBgTransition'
                                    min={ 0 }
                                    max={ 3 }
                                    step={0.1}
                                    responsiveNo={true}
                                    unitNo={true}
                                />
                            </>
                        )}
                    </>}
                </TabPanel>
            </PanelBody>
            <PanelBody
                title={__('Custom CSS','directory-plugin')}
                initialOpen={false}
            >
                <DpCustomCss {...{attributes, setAttributes}} 
                    label={__('Custom CSS','directory-plugin')}
                    attrId = 'wrapperCustomCss'
                />
            </PanelBody>
        </>
    )
}
