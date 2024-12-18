import React, {useEffect} from 'react';
import axios from "axios";
import Router from "../routes/Router";
import UserProvider from "../context/UserProvider";
import {QueryClient, QueryClientProvider} from "@tanstack/react-query";
import {ThemeProvider} from "@mui/material";
import CssBaseline from '@mui/material/CssBaseline';
import theme from "../theme";
import '../i18n';
import Cookies from 'js-cookie';
import {useTranslation} from "react-i18next";
import {ReactQueryDevtools} from "@tanstack/react-query-devtools";

axios.defaults.baseURL = "/api";

const queryClient = new QueryClient();

export default function App() {
	const {i18n} = useTranslation();

	useEffect(() => {
		Cookies.set('lang', i18n.language, {expires: 7});
	}, []);

	return (
		<ThemeProvider theme={theme}>
			<CssBaseline/>
			<QueryClientProvider client={queryClient}>
				<UserProvider>
					<Router/>
				</UserProvider>
				<ReactQueryDevtools initialIsOpen={false}/>
			</QueryClientProvider>
		</ThemeProvider>
	);
}

// ReactDom.render(<App />, document.getElementById('root'));
