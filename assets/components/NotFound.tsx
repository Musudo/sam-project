import React from 'react';
import {Box, Typography} from '@mui/material';
import Grid from '@mui/material/Grid';

export default function NotFound() {

	return (
		<Box sx={{
			display: 'flex',
			flexDirection: 'column',
			justifyContent: 'center',
			alignItems: 'center',
			textAlign: 'center',
			minHeight: '77vh',
			padding: 2
		}}>
			<Grid container spacing={2} direction="column">
				<Grid mb={4}>
					<Typography variant="h1" color="black" gutterBottom>
						404
					</Typography>
					<Typography variant="h4" color="black" gutterBottom>
						Page not found.
					</Typography>
					<Typography variant="body2" color="black" gutterBottom>
						The page you're looking for may have been removed, its name changed, or it's temporary
						not available.
					</Typography>
				</Grid>
			</Grid>
		</Box>
	);
}