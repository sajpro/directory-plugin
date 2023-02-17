import { useCallback } from '@wordpress/element'
import { dispatch } from '@wordpress/data';

export const useSetDevice = () => {
    const FbSetPreviewDevice = useCallback( device => {
        const {
            __experimentalSetPreviewDeviceType: setPreviewDeviceType,
        } = dispatch( 'core/edit-post' )
        setPreviewDeviceType( device );
    }, [] )
    return FbSetPreviewDevice;
}
