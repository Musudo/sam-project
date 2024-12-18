import React, {Dispatch, SetStateAction} from 'react';
import {Alert, Snackbar} from "@mui/material";

interface Props {
	open: boolean;
	setOpen: Dispatch<SetStateAction<boolean>>;
	message: string;
}

export function FormSubmitSnackbar(props: Props) {

	const handleCloseSnackbar = (event?: React.SyntheticEvent | Event, reason?: string) => {
		if (reason === 'clickaway') return;
		props.setOpen(false);
	};

	return (
		<Snackbar open={props.open}
				  autoHideDuration={4000}
				  anchorOrigin={{vertical: "bottom", horizontal: "left"}}
				  onClose={handleCloseSnackbar}>
			<Alert onClose={handleCloseSnackbar} severity="success" sx={{width: '100%'}}>
				{props.message}
			</Alert>
		</Snackbar>
	);
}