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
            {/* {attributes[attributesId].url && (
                <span className="dp-remove-media" onClick={()=> setAttributes({ [attributesId]:{...attributes[attributesId],url:'', id:''} })}>&times;</span>
            )} */}
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
                                label={__("Upload Image", "dp-blocks")}
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
                            label={__('Position','dp-blocks')}
                        >
                        <DpResponsiveControls {...{attributes, setAttributes,attributesId}}/>
                        <SelectControl
                            className='dp-flex'
                            value={ attributes[attributesId][getDevice].position }
                            onChange={ v => setAttributes({ [attributesId]:{...attributes[attributesId], [getDevice]: {...attributes[attributesId][getDevice], position: v}} }) }
                            options={ [
                                { label: 'Default', value: '' },
                                { label: 'Center Center', value: 'center center' },
                                { label: 'Center Left', value: 'center left' },
                                { label: 'Center Right', value: 'center right' },
                                { label: 'Top Center', value: 'top center' },
                                { label: 'Top Left', value: 'top left' },
                                { label: 'Top Right', value: 'top right' },
                                { label: 'Bottom Center', value: 'bottom center' },
                                { label: 'Bottom Left', value: 'bottom left' },
                                { label: 'Bottom Right', value: 'bottom right' }
                            ] }
                        /> 
                    </BaseControl>
                    <BaseControl
                        label={__('Attachment','dp-blocks')}
                        >
                        <SelectControl
                            className='dp-flex'
                            value={ attributes[attributesId].attachment }
                            onChange={ v => setAttributes({ [attributesId]:{...attributes[attributesId],attachment:v} }) }
                            options={ [
                                { label: 'Default', value: '' },
                                { label: 'Scroll', value: 'scroll' },
                                { label: 'Fixed', value: 'fixed' }
                            ] }
                        /> 
                    </BaseControl>
                    <BaseControl
                        label={__('Repeat','dp-blocks')}
                    >
                        <DpResponsiveControls {...{attributes, setAttributes,attributesId}}/>
                        <SelectControl
                            className='dp-flex'
                            value={ attributes[attributesId][getDevice].repeat }
                            onChange={ v => setAttributes({ [attributesId]:{...attributes[attributesId], [getDevice]: {...attributes[attributesId][getDevice], repeat: v}} }) }
                            options={ [
                                { label: 'Default', value: '' },
                                { label: 'No Repeat', value: 'no-repeat' },
                                { label: 'Repeat', value: 'repeat' },
                                { label: 'Repeat-X', value: 'repeat-x' },
                                { label: 'Repeat-Y', value: 'repeat-y' }
                            ] }
                        />  
                    </BaseControl>
                    <BaseControl
                        label={__('Size','dp-blocks')}
                    >
                        <DpResponsiveControls {...{attributes, setAttributes,attributesId}}/>
                        <SelectControl
                            className='dp-flex'
                            value={ attributes[attributesId][getDevice].size }
                            onChange={ v => setAttributes({ [attributesId]:{...attributes[attributesId], [getDevice]: {...attributes[attributesId][getDevice], size: v}} }) }
                            options={ [
                                { label: 'Default', value: '' },
                                { label: 'Auto', value: 'auto' },
                                { label: 'Cover', value: 'cover' },
                                { label: 'Contain', value: 'contain' },
                                { label: 'Custom', value: 'custom' }
                            ] }
                        /> 
                    </BaseControl>
                    {(attributes[attributesId][getDevice]?.size) == 'custom' && (
                        <BaseControl
                            label={'Width'}
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
