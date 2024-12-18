import {Alert, Box, Typography} from '@mui/material';
import Grid from '@mui/material/Grid';
import React from 'react';
import {ErrorTypesEnum} from '../enums/ErrorTypesEnum';

interface Props {
	type: ErrorTypesEnum;
}

export function ErrorComponent({type}: Props) {
	let message = '';

	switch (type) {
		case ErrorTypesEnum.Fetch:
			message = 'Something went wrong while fetching data';
			break;
		case ErrorTypesEnum.Post:
			message = 'Something went wrong while creating new record';
			break;
		case ErrorTypesEnum.Patch:
			message = 'Something went wrong while updating record';
			break;
		case ErrorTypesEnum.Delete:
			message = 'Something went wrong while deleting record';
			break;
		case ErrorTypesEnum.General:
			message = 'An unknown error has occurred';
			break;
	}

	return (
		<>
			<Alert severity='error'>{message}</Alert>
			<Box
				sx={{
					display: 'flex',
					flexDirection: 'column',
					justifyContent: 'center',
					alignItems: 'center',
					textAlign: 'center',
					minHeight: '77vh',
					padding: 2
				}}>
				<Grid container spacing={2} direction='column'>
					<Grid mb={4}>
						<Typography variant='h4' color='black' gutterBottom>
							Error.
						</Typography>
						<Typography variant='body2' color='black' gutterBottom>
							Sorry, we can't complete your request right now. Please try again later.
						</Typography>
					</Grid>
				</Grid>
			</Box>
		</>
	);
}