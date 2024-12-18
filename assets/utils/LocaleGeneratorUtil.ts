/**
 * Generates proper locale value from current locale language for MUI DateTimePicker
 * @param language
 */
export function getLocale(language: string) {
	let locale: string;

	// all other languages except 'en' are the same as in i18n locale,
	// so there should be extra check for this one
	if (language.includes('en')) {
		locale = 'en';
	} else {
		locale = language;
	}

	return locale;
}