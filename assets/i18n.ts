import i18n from 'i18next';
import {initReactI18next} from "react-i18next";
import languageDetector from "i18next-browser-languagedetector";
import backend from "i18next-http-backend";

i18n
	.use(initReactI18next)
	.use(languageDetector)
	.use(backend)
	.init({
		debug: process.env.NODE_ENV === 'development',
		fallbackLng: 'en',
		interpolation: {
			escapeValue: false // react already safes from xss
		},
		backend: {
			allowMultiLoading: true,
			loadPath: '/locales/{{lng}}/{{ns}}.json'
		}
	});

export default i18n;