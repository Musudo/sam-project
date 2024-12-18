import {Card, CardActionArea, CardContent, Typography} from "@mui/material";
import React from "react";
import {IActivity} from "../../../models/IActivity";
import dayjs from "dayjs";
import {useNavigate} from "react-router-dom";

interface Props {
	activity: IActivity;
}

export default function ArchivedActivityCard({activity}: Props) {
	const navigate = useNavigate();

	return (
		<Card sx={{width: 210, height: 120}} onClick={() => navigate(`/activities/${activity.guid}`)}>
			<CardActionArea sx={{width: 210, height: 120}}>
				<CardContent>
					<Typography sx={{fontSize: 12}} color="text.secondary" gutterBottom>
						{activity.type}
					</Typography>
					<Typography variant="h6" sx={{fontSize: 14}} component="div" noWrap>
						{activity.subject}
					</Typography>
					<Typography sx={{fontSize: 14}} color="text.secondary">
						{dayjs(activity.start).format('DD MMM YYYY HH:mm')}
					</Typography>
				</CardContent>
			</CardActionArea>
		</Card>
	);
}