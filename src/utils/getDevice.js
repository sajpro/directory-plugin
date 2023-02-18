import { useSelect } from '@wordpress/data'

export const useGetDevice = () => {
	const { deviceType } = useSelect(
		select => {
			return {
				deviceType: select(
					'core/edit-post'
				)?.__experimentalGetPreviewDeviceType() || 'Desktop',
			}
		},
		[]
	)

	return deviceType || ''
}