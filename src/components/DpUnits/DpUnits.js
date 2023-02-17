import { Button } from "@wordpress/components";
import { useGetDevice } from "../../utils/getDevice";

export const DpUnits = (props) => {
    let { attributes, setAttributes,attributesId, unitExclude } = props;

    const getDevice = useGetDevice();

    const handleUnit = (v) => {
        let newUnit = {...attributes[attributesId], unitDefault: v, [getDevice]: {...attributes[attributesId][getDevice],unit: v}}
        setAttributes({[attributesId]: newUnit})
    }

    let units = ['px','em','%', 'rem'];

    return (
        <div className="dp-units">
            {units.map((btn,i)=>(
                <Button
                    key={i}
                    className={(attributes[attributesId][getDevice]?.unit || attributes[attributesId].unitDefault ) == btn ? 'active-unit' : ''}
                    onClick={(v) => handleUnit(btn)}
                >{btn}</Button>  
            ))}
        </div>
    );
}
