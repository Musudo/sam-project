/**
 * Makes the first letter capital
 * @param param
 */
export function capitalizeFirstLetter(param: string) {
	return param.charAt(0).toUpperCase() + param.slice(1);
}

/**
 * Makes the first letter non-capital
 * @param param
 */
export function unCapitalizeFirstLetter(param: string) {
	return param.charAt(0).toLowerCase() + param.slice(1);
}