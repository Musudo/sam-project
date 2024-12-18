/**
 * MUI tab element props helper function
 * @param index
 */
export function allyProps(index: number) {
	return {
		id: `action-tab-${index}`,
		'aria-controls': `action-tabpanel-${index}`,
	};
}

/**
 * Multiselect menu props
 */
export const MenuProps = {
	PaperProps: {
		style: {
			maxHeight: 48 * 4.5 + 8,
			width: 250,
		},
	}
}