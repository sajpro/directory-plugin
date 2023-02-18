import { __ } from "@wordpress/i18n";
import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
    TabPanel
} from '@wordpress/components';

import { DpRangeControl } from "../../components/DpRangeControl";
import { DpToggleControl } from "../../components/DpToggleControl";
import { DpColorPalette } from "../../components/DpColorPalette";
import { DpTypography } from "../../components/DpTypography";
import { DpSpacingControls } from "../../components/DpSpacingControls";

import { AdvancedControls } from "../../utils/AdvancedControls"


export const TAB_BUTTONS = [
    {
        name: 'general',
        title: __('General','directory-plugin'),
        className: 'dp-tab general',
    },
    {
        name: 'style',
        title: __('Style','directory-plugin'),
        className: 'dp-tab styles',
    },
    {
        name: 'advanced',
        title: __('Advanced','directory-plugin'),
        className: 'dp-tab advanced',
    },
];

const Inspector = (props) => {
    let {attributes,setAttributes} = props;

    let {
        title, 
        subtitle,
    } = attributes;

    return (
        <InspectorControls>
            <TabPanel
					className='dp-inspector-tab-panel'
					activeClass='active-tab'
					tabs={TAB_BUTTONS}
            >
                {(tab)=> <>
                    {(tab.name == 'general') && (
                        <PanelBody title={ __( 'Advanced', 'directory-plugin' ) }>
                            <TextControl
                                label={ __( 'Title', 'directory-plugin' ) }
                                value={title}
                                onChange={ (v) =>
                                    setAttributes( { title: v } )
                                }
                            />
                            <TextControl
                                label={ __( 'Subitle', 'directory-plugin' ) }
                                value={subtitle}
                                onChange={ (v) =>
                                    setAttributes( { subtitle: v } )
                                }
                            />
                            <DpRangeControl {...{attributes, setAttributes}}
                                label={__('Number of Listings to Show','directory-plugin')}
                                attributesId = 'number'
                                min="1"
                                max="20"
                                step="1"
                            />
                            <DpToggleControl {...{attributes, setAttributes}} 
                                label={__('Display Pagination?','directory-plugin')}
                                attributesId = 'showPagination'
                            />
                            <DpToggleControl {...{attributes, setAttributes}} 
                                label={__('Display Submit Button?','directory-plugin')}
                                attributesId = 'showSubmitButton'
                            />
                        </PanelBody>
                    )}
                    {(tab.name == 'style') && (
                        <>
                            <PanelBody 
                            className="dp-style-tab"
                            title={ __( 'Section Title', 'directory-plugin' ) }
                            >
                                <DpTypography {...{attributes, setAttributes}}
                                    label={__('Typography','directory-plugin')}
                                    attributesId = 'secTitleTypography'
                                />
                                <TabPanel
                                    className='sec-tab-panel'
                                    activeClass='active-tab'
                                    tabs={[
                                        {
                                            name: 'normal',
                                            title: __('Normal','directory-plugin'),
                                        },
                                        {
                                            name: 'hover',
                                            title: __('Hover','directory-plugin'),
                                        }
                                    ]}
                                >
                                    {(tab)=> <>
                                        {(tab.name == 'normal') && (
                                            <DpColorPalette {...{attributes, setAttributes}}
                                                label={__('Text Color','directory-plugin')}
                                                attributesId = 'secTitleNormalColor'
                                            />
                                        )}
                                        {(tab.name == 'hover') && (
                                            <>
                                                <DpColorPalette {...{attributes, setAttributes}}
                                                    label={__('Text Color','directory-plugin')}
                                                    attributesId = 'secTitleHoverColor'
                                                />
                                                <DpRangeControl {...{attributes, setAttributes}}
                                                    label={__('Transition Duration','directory-plugin')}
                                                    attributesId = 'secTitleTransition'
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
                                <DpSpacingControls {...{attributes, setAttributes}}
                                    label={__('Padding','directory-plugin')}
                                    attributesId = 'secTitlePadding'
                                    className = 'dp-not-flex'
                                />
                            </PanelBody>
                            <PanelBody 
                            className="dp-style-tab"
                            title={ __( 'Section Subtitle', 'directory-plugin' ) }
                            initialOpen={false}
                            >
                                <DpTypography {...{attributes, setAttributes}}
                                    label={__('Typography','directory-plugin')}
                                    attributesId = 'secSubtitleTypography'
                                />
                                <TabPanel
                                    className='sec-tab-panel'
                                    activeClass='active-tab'
                                    tabs={[
                                        {
                                            name: 'normal',
                                            title: __('Normal','directory-plugin'),
                                        },
                                        {
                                            name: 'hover',
                                            title: __('Hover','directory-plugin'),
                                        }
                                    ]}
                                >
                                    {(tab)=> <>
                                        {(tab.name == 'normal') && (
                                            <DpColorPalette {...{attributes, setAttributes}}
                                                label={__('Text Color','directory-plugin')}
                                                attributesId = 'secSubtitleNormalColor'
                                            />
                                        )}
                                        {(tab.name == 'hover') && (
                                            <>
                                                <DpColorPalette {...{attributes, setAttributes}}
                                                    label={__('Text Color','directory-plugin')}
                                                    attributesId = 'secSubtitleHoverColor'
                                                />
                                                <DpRangeControl {...{attributes, setAttributes}}
                                                    label={__('Transition Duration','directory-plugin')}
                                                    attributesId = 'secSubtitleTransition'
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
                                <DpSpacingControls {...{attributes, setAttributes}}
                                    label={__('Padding','directory-plugin')}
                                    attributesId = 'secSubtitlePadding'
                                    className = 'dp-not-flex'
                                />
                            </PanelBody>
                        </>
                    )}
                    {(tab.name == 'advanced') && (
                        <AdvancedControls {...{attributes, setAttributes}}/>
                    )}
                </>}
            </TabPanel>
        </InspectorControls>
    )
}

export default Inspector