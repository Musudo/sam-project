import React from "react";
import Typography from '@mui/material/Typography';
import {alpha, Grid, List, ListItem, ListItemIcon, ListItemText} from "@mui/material";
import {IActivity} from "../../models/IActivity";
import dayjs from "dayjs";
import {useNavigate} from "react-router-dom";
import ConnectWithoutContactIcon from '@mui/icons-material/ConnectWithoutContact';
import LocalPhoneIcon from '@mui/icons-material/LocalPhone';
import PeopleIcon from '@mui/icons-material/People';
import {useTranslation} from "react-i18next";

interface Props {
	activities: IActivity[];
}

export function NextActivities({activities}: Props) {
	const navigate = useNavigate();
	const {t} = useTranslation();
	let nextActivities;

	if (activities && activities.length > 0) {
		activities.sort((a: IActivity, b: IActivity) => dayjs(a.start).unix() - dayjs(b.start).unix());
		nextActivities = activities.slice(0, 4);
	}

	return (
		<>
			<Typography component="h2" variant="h6" color="primary" gutterBottom>
				{t('Dashboard page.Next activities')}
			</Typography>
			<Grid container>
				{
					(nextActivities && nextActivities.length > 0) && nextActivities.map(activity => (
						<>
							<List sx={{width: '100%', maxWidth: 800, bgcolor: 'background.paper'}} dense disablePadding>
								<ListItem key={activity.id}>
									<ListItemIcon>
										{
											activity?.type === 'Phone conversation' && (
												<Typography color="text.secondary">
													<LocalPhoneIcon/>
												</Typography>
											)
										}
										{
											activity?.type === 'Online' && (
												<Typography color="text.secondary">
													<ConnectWithoutContactIcon/>
												</Typography>
											)
										}
										{
											activity?.type === 'Physical meeting' && (
												<Typography color="text.secondary">
													<PeopleIcon/>
												</Typography>
											)
										}
									</ListItemIcon>
									<ListItemText
										sx={{
											cursor: "pointer",
											":hover": {backgroundColor: alpha("#bdbdbd", 0.10), borderRadius: "2px"}
										}}
										primary={
											<Typography style={{color: "#2a3eb1"}}>
												{activity.subject}
											</Typography>}
										secondary={
											<span style={{color: "#2a3eb1"}}>
												{dayjs(activity?.start)
													.format('DD MMM YYYY HH:mm')}, {activity?.institution?.name}
											</span>
										}
										onClick={() => navigate(`/activities/${activity.guid}`)}/>
								</ListItem>
							</List>
						</>
					))
				}
			</Grid>
		</>
	);
}