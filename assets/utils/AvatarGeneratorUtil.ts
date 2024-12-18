/**
 * Creates a background with random color for given string parameter
 * @param param
 */
function stringToColor(param: string) {
	let hash = 0;
	let i;

	/* eslint-disable no-bitwise */
	for (i = 0; i < param.length; i += 1) {
		hash = param.charCodeAt(i) + ((hash << 5) - hash);
	}

	let color = '#';

	/* eslint-enable no-bitwise */
	for (i = 0; i < 3; i += 1) {
		const value = (hash >> (i * 8)) & 0xff;
		color += `00${value.toString(16)}`.slice(-2);
	}

	return color;
}

/**
 * Returns avatar with colored background for given name
 * @param name
 */
export function stringColoredAvatar(name: string): { sx: { bgcolor: string }, children: string } {
	return {
		sx: {
			bgcolor: stringToColor(name),
		},
		children: `${name.split(' ')[0][0]}${name.split(' ')[1][0]}`
	};
}

/**
 * Returns avatar with simple grey background for given name
 * @param name
 */
export function stringAvatar(name: string): { children: string } {
	return {
		children: `${name.split(' ')[0][0]}${name.split(' ')[1][0]}`
	};
}
