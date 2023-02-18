import { Button, ButtonGroup, BaseControl } from "@wordpress/components";

export const DpButtonGroup = (props) => {
    let { attributes, setAttributes,attributesId, buttons,label } = props;

    return (
        <BaseControl
            label={label || ''}
        >
            <ButtonGroup className="dp-btn-group">
                {buttons.map((button,i) => (
                    <Button
                        key={i}
                        className={`${attributes[attributesId] === button.value ? 'active-tab' : ''}`}
                        onClick={() => setAttributes({[attributesId]: button.value})}
                        icon={button?.icon}
                    >
                        {button?.label}
                    </Button>
                ))}
            </ButtonGroup>
        </BaseControl>
    );
}
