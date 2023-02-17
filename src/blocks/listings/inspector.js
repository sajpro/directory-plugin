import { __ } from "@wordpress/i18n";
import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
    TabPanel
} from '@wordpress/components';

import { DpRangeControl } from "../../components/DpRangeControl";
import { DpToggleControl } from "../../components/DpToggleControl";

export const TAB_BUTTONS = [
    {
        name: 'general',
        title: 'General',
        className: 'fb-tab general',
    },
    {
        name: 'style',
        title: 'Style',
        className: 'fb-tab styles',
    },
    {
        name: 'advanced',
        title: 'Advanced',
        className: 'fb-tab advanced',
    },
];

const Inspector = (props) => {
    let {attributes,setAttributes} = props;

    let {
        title, 
        subtitle,
        number,
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
                                attrId = 'number'
                                min="1"
                                max="20"
                                step="1"
                            />
                            <DpToggleControl {...{attributes, setAttributes}} 
                                label={__('Display Pagination?','directory-plugin')}
                                attrId = 'pagination'
                            />
                            <DpToggleControl {...{attributes, setAttributes}} 
                                label={__('Display Submit Button?','directory-plugin')}
                                attrId = 'submitButton'
                            />
                        </PanelBody>
                    )}
                    {(tab.name == 'style') && (
                        <h2>style</h2>
                    )}
                    {(tab.name == 'advanced') && (
                        <h2>advanced</h2>
                    )}
                </>}
            </TabPanel>
        </InspectorControls>
    )
}

export default Inspector