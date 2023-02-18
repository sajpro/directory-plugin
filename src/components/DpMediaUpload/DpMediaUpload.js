import { Button, SelectControl, BaseControl,RangeControl } from "@wordpress/components";
import { MediaUpload } from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";
import { FaTrashAlt } from "react-icons/fa";
import {DpUnits} from "../DpUnits"
import DpResponsiveControls from "../DpResponsiveControls/DpResponsiveControls"
import { useGetDevice } from "../../utils/getDevice";

export const DpMediaUpload = (props) => {

    const getDevice = useGetDevice();

    let { attributes, setAttributes,attributesId, label } = props;

    return (
        <BaseControl
            label={label}
            className='dp-inspector-image-control dp-not-flex'
        >
            <MediaUpload
                onSelect={({ id, url }) =>
                    setAttributes({ [attributesId]:{...attributes[attributesId],url, id} })
                }
                type="image"
                value={attributes[attributesId].id}
                render={({ open }) => {
                    if (!attributes[attributesId].url) {
                        return (
                            <Button
                                className="image-uploader"
                                label={__("Upload Image", "directory-plugin")}
                                icon="format-image"
                                onClick={open}
                            />
                        );
                    } else {
                        return (
                            <div className="image-wrapper">
                                <span className="dp-remove-media" onClick={()=> setAttributes({ [attributesId]:{...attributes[attributesId],url:'', id:''} })}><FaTrashAlt/></span>
                                <div className="background-image" onClick={open}>
                                    <img className="image" alt="dp-blocks" src={attributes[attributesId].url} />
                                </div>
                            </div>
                        );
                    }
                }}
            />
            {attributes[attributesId].url && (
                <>
                    <BaseControl
                            label={__('Position','directory-plugin')}
                        >
                        <DpResponsiveControls {...{attributes, setAttributes,attributesId}}/>
                        <SelectControl
                            className='dp-flex'
                            value={ attributes[attributesId][getDevice].position }
                            onChange={ v => setAttributes({ [attributesId]:{...attributes[attributesId], [getDevice]: {...attributes[attributesId][getDevice], position: v}} }) }
                            options={ [
                                { label: __('Default','directory-plugin'), value: '' },
                                { label: __('Center Center','directory-plugin'), value: 'center center' },
                                { label: __('Center Left','directory-plugin'), value: 'center left' },
                                { label: __('Center Right','directory-plugin'), value: 'center right' },
                                { label: __('Top Center','directory-plugin'), value: 'top center' },
                                { label: __('Top Left','directory-plugin'), value: 'top left' },
                                { label: __('Top Right','directory-plugin'), value: 'top right' },
                                { label: __('Bottom Center','directory-plugin'), value: 'bottom center' },
                                { label: __('Bottom Left','directory-plugin'), value: 'bottom left' },
                                { label: __('Bottom Right','directory-plugin'), value: 'bottom right' }
                            ] }
                        /> 
                    </BaseControl>
                    <BaseControl
                        label={__('Attachment','directory-plugin')}
                        >
                        <SelectControl
                            className='dp-flex'
                            value={ attributes[attributesId].attachment }
                            onChange={ v => setAttributes({ [attributesId]:{...attributes[attributesId],attachment:v} }) }
                            options={ [
                                { label: __('Default','directory-plugin'), value: '' },
                                { label: __('Scroll','directory-plugin'), value: 'scroll' },
                                { label: __('Fixed','directory-plugin'), value: 'fixed' }
                            ] }
                        /> 
                    </BaseControl>
                    <BaseControl
                        label={__('Repeat','directory-plugin')}
                    >
                        <DpResponsiveControls {...{attributes, setAttributes,attributesId}}/>
                        <SelectControl
                            className='dp-flex'
                            value={ attributes[attributesId][getDevice].repeat }
                            onChange={ v => setAttributes({ [attributesId]:{...attributes[attributesId], [getDevice]: {...attributes[attributesId][getDevice], repeat: v}} }) }
                            options={ [
                                { label: __('Default','directory-plugin'), value: '' },
                                { label: __('No Repeat','directory-plugin'), value: 'no-repeat' },
                                { label: __('Repeat','directory-plugin'), value: 'repeat' },
                                { label: __('Repeat-X','directory-plugin'), value: 'repeat-x' },
                                { label: __('Repeat-Y','directory-plugin'), value: 'repeat-y' }
                            ] }
                        />  
                    </BaseControl>
                    <BaseControl
                        label={__('Size','directory-plugin')}
                    >
                        <DpResponsiveControls {...{attributes, setAttributes,attributesId}}/>
                        <SelectControl
                            className='dp-flex'
                            value={ attributes[attributesId][getDevice].size }
                            onChange={ v => setAttributes({ [attributesId]:{...attributes[attributesId], [getDevice]: {...attributes[attributesId][getDevice], size: v}} }) }
                            options={ [
                                { label: __('Default','directory-plugin'), value: '' },
                                { label: __('Auto','directory-plugin'), value: 'auto' },
                                { label: __('Cover','directory-plugin'), value: 'cover' },
                                { label: __('Contain','directory-plugin'), value: 'contain' },
                                { label: __('Custom','directory-plugin'), value: 'custom' }
                            ] }
                        /> 
                    </BaseControl>
                    {(attributes[attributesId][getDevice]?.size) == 'custom' && (
                        <BaseControl
                            label={__('Width','directory-plugin')}
                            className={'dp-css-value-wrapper dp-not-flex'}
                        >

                            <div className="units-responsive">
                                <DpResponsiveControls {...{attributes, setAttributes,attributesId}}/>
                                <DpUnits {...{attributes, setAttributes,attributesId}}/>
                            </div>

                            <RangeControl
                                value={ attributes[attributesId][getDevice].custom_size }
                                className={'dp-mt-8'}
                                onChange={ v => setAttributes({ [attributesId]:{...attributes[attributesId], [getDevice]: {...attributes[attributesId][getDevice], custom_size: v}} }) }
                                min={ 0 }
                                max={ 2000 }
                            />
                        </BaseControl>
                    )}
                </>
            )}
        </BaseControl>
    );
}
