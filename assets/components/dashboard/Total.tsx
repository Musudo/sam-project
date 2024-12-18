import React from "react";
import Typography from '@mui/material/Typography';
import {Divider, Grid} from "@mui/material";
import {t} from "i18next";

interface Props {
	totalActivities: number;
	totalTasks: number;
}

export function Total({totalActivities, totalTasks}: Props) {

	return (
		<>
			<Typography component="h2" variant="h6" color="primary" gutterBottom>
				{t('Dashboard page.Total')}
			</Typography>
			<Grid container>
				<Grid item xs>
					<Typography component="p" variant="h6" align="center">
						{t('Dashboard page.Total card.Activities')}
					</Typography>
					<Typography color="text.secondary" variant="h3" sx={{flex: 1}} align="center">
						{totalActivities}
					</Typography>
				</Grid>
				<Divider orientation="vertical" flexItem textAlign="center">
				</Divider>
				<Grid item xs>
					<Typography component="p" variant="h6" align="center">
						{t('Dashboard page.Total card.Tasks')}
					</Typography>
					<Typography color="text.secondary" variant="h3" sx={{flex: 1}} align="center">
						{totalTasks}
					</Typography>
				</Grid>
			</Grid>
		</>
	);
}