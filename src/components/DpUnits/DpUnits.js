import { Button } from "@wordpress/components";
import { useGetDevice } from "../../utils/getDevice";

export const DpUnits = (props) => {
    let { attributes, setAttributes,attributesId, unitExclude } = props;

    const getDevice = useGetDevice();

    const handleUnit = (v) => {
    }

    let units = ['px','em','%', 'rem'];

    return (
        <div className="dp-units">
            {units.map((btn,i)=>(
                <Button
                    key={i}
                    className={'active'}
                    onClick={(v) => handleUnit(btn)}
                >{btn}</Button>  
            ))}
        </div>
    );
}
