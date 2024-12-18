import {CircularProgress, Grid} from "@mui/material";
import React from "react";

export default function LoadingComponent() {
	return (
		<Grid
			container
			spacing={0}
			direction="column"
			alignItems="center"
			justifyContent="center"
			style={{minHeight: '80vh'}}
		>
			<CircularProgress/>
		</Grid>
	);
}