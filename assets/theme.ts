import {createTheme} from "@mui/material";

const theme = createTheme({
	palette: {
		mode: 'light',
	},
	breakpoints: {
		values: {
			xs: 0,    // extra-small devices (portrait phones)
			sm: 600,  // small devices (landscape phones)
			md: 960,  // medium devices (tablets)
			lg: 1280, // large devices (laptops/desktops)
			xl: 1920, // extra-large devices (large desktops)
		},
	},
});

export default theme;