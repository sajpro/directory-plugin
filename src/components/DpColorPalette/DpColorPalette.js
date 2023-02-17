import { BaseControl, ColorPicker, Dropdown, Button, Icon } from "@wordpress/components";
import { pencil,reset } from "@wordpress/icons";
import { __ } from "@wordpress/i18n";

export const DpColorPalette = (props) => {
    let {attributes,setAttributes,attributesId,label} = props;
    return (
        <BaseControl
            label={label}
        >
            <Dropdown
                contentClassName="color-popover-control"
                position="bottom left"
                renderToggle={ ( { isOpen, onToggle } ) => (
                    <Button
                        variant="primary"
                        className="dp-color-picker"
                        onClick={ onToggle }
                        aria-expanded={ isOpen }
                        // icon={pencil}
                        style={{backgroundColor:attributes[attributesId]}}
                    />
                ) }
                renderContent={ () => <>
                    <div className="dp-color-palette-reset">
                        <span>Color</span>
                        <Icon
                            onClick={() => setAttributes({ [attributesId]: '' })}
                            icon={reset}
                        />
                    </div>
                    <ColorPicker
                        color={attributes[attributesId]}
                        onChange={(color) => setAttributes({ [attributesId]: color })}
                        enableAlpha
                        defaultValue={attributes[attributesId]}
                    />
                </> }
            />
        </BaseControl>
    );
}