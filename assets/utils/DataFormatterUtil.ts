/**
 * Returns properly formatted full name or returns 'na' if data is inconsistent
 * @param firstName
 * @param lastName
 */
export function nameFormatter(firstName: string, lastName: string): string {
	const isFirstNameValid = validateField(firstName);
	const isLastNameValid = validateField(lastName);

	if (!isFirstNameValid && !isLastNameValid) {
		return 'na';
	} else if (!isLastNameValid) {
		return `${firstName} na`;
	} else if (!isFirstNameValid) {
		return `na ${lastName}`;
	}

	return `${firstName} ${lastName}`;
}

/**
 * Returns data field if it is consistent, otherwise returns 'na'
 * @param dataField
 */
export function dataFieldFormatter(dataField: string): string {
	const isDataFieldValid = validateField(dataField);

	if (!isDataFieldValid) {
		return 'na';
	}

	return dataField;
}

/**
 * Returns properly formatted address
 * @param country
 * @param city
 * @param zipCode
 * @param street
 * @param houseNumber
 * @param postBox
 */
export function addressFormatter(country: string, city: string, zipCode: string, street: string,
								 houseNumber: string, postBox: string = ""): string {
	const isHouseNumberValid = validateField(houseNumber);
	const isPostBoxValid = validateField(postBox);

	if (!isHouseNumberValid || !isPostBoxValid) {
		return `${country}, ${city} ${zipCode}, ${street}`;
	}

	return `${country}, ${city} ${zipCode}, ${street} ${houseNumber} ${postBox}`;
}

/**
 * Executes validation check on data field
 * @param param
 */
function validateField(param: string): boolean {
	return param !== 'empty' && param !== '--' && param !== '';
}