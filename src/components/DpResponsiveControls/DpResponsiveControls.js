import { Icon } from "@wordpress/components";
import { desktop, tablet, mobile } from "@wordpress/icons";

import { useGetDevice } from "../../utils/getDevice";
import { useSetDevice } from "../../utils/setDevice";

const DpResponsiveControls = () => {
    const deviceIcon = [
        { value: "Desktop", icon: desktop },
        { value: "Tablet", icon: tablet },
        { value: "Mobile", icon: mobile }
    ];

    const getDevice = useGetDevice();
    const setDevice = useSetDevice();

    return (
        <div className="device-responsive-icons" style={{display:"flex"}}>
            {deviceIcon.map((device, i) => (
                <div
                    key={i}
                    onClick={() => setDevice(device.value)}
                    className={`dp-device-icon ${
                        getDevice === device.value ? 'active' : ''
                    }`}
                >
                    <Icon icon={device.icon} />
                </div>
            ))}
        </div>
    );
};

export default DpResponsiveControls;
