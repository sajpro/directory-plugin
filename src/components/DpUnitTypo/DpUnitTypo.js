import { Button } from "@wordpress/components";
import { useGetDevice } from "../../utils/getDevice";

export const DpUnitTypo = (props) => {
    let { attributes, setAttributes,attributesId, attributesUnit, attributesUnitDefault } = props;

    const getDevice = useGetDevice();

    const handleUnit = (v) => {
        let newUnit = {...attributes[attributesId], [attributesUnitDefault] : v ,[getDevice]: {...attributes[attributesId][getDevice],[attributesUnit]:{...attributes[attributesId][getDevice][attributesUnit],unit: v}} }
        setAttributes({[attributesId]: newUnit})
    }

    return (
        <div className='dp-units'>
            <Button
                className={(attributes[attributesId][getDevice][attributesUnit]?.unit || attributes[attributesId][attributesUnitDefault] ) == 'px' ? 'active-unit' : ''}
                onClick={(v) => handleUnit('px')}
            >px</Button>  
            <Button
                className={(attributes[attributesId][getDevice][attributesUnit]?.unit || attributes[attributesId][attributesUnitDefault] ) == 'em' ? 'active-unit' : ''}
                onClick={(v) => handleUnit('em')}
            >em</Button>  
            <Button
                className={(attributes[attributesId][getDevice][attributesUnit]?.unit || attributes[attributesId][attributesUnitDefault] ) == '%' ? 'active-unit' : ''}
                onClick={(v) => handleUnit('%')}
            >%</Button> 
        </div>
    );
}
