import { Button } from "@wordpress/components";
import { useGetDevice } from "../../utils/getDevice";

export const DpUnitTypo = (props) => {
    let { attributes, setAttributes,attributesId, attributesUnit, attributesUnitDefault } = props;

    const getDevice = useGetDevice();

    const handleUnit = (v) => {
        let newUnit = {...attributes[attributesId], [attributesUnitDefault] : v ,[getDevice]: {...attributes[attributesId][getDevice],[attributesUnit]:{...attributes[attributesId][getDevice][attributesUnit],unit: v}} }
        setAttributes({[attributesId]: newUnit})
    }

    let units = ['px','em','%', 'rem'];

    return (
        <div className='dp-units'>
            {units.map((btn,i)=>( 
                <Button
                key={i}
                    className={(attributes[attributesId][getDevice][attributesUnit]?.unit || attributes[attributesId][attributesUnitDefault] ) == btn ? 'active-unit' : ''}
                    onClick={(v) => handleUnit(btn)}
                >{btn}</Button> 
            ))}
        </div>
    );
}
