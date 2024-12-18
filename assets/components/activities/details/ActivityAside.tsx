import * as React from 'react';
import {Box, Button, Divider, Typography} from '@mui/material';
import EditIcon from '@mui/icons-material/Edit';
import {useNavigate} from "react-router-dom";
import {IActivity} from "../../../models/IActivity";
import {addressFormatter} from "../../../utils/DataFormatterUtil";

interface Props {
	activity: IActivity;
}

export function ActivityAside({activity}: Props) {
	const navigate = useNavigate();

	let address = "";
	if (activity.institution) {
		address = addressFormatter(activity.institution.country, activity.institution.city,
			activity.institution.zipCode, activity.institution.street, activity.institution.houseNumber);
	}

	return (
		<Box ml={4} width={250} minWidth={250}>
			<Box textAlign="left" mb={2}>
				<Button type="button" startIcon={<EditIcon/>}
						onClick={() => navigate(`/activities/edit/${activity.guid}`)}>Edit Activity</Button>
			</Box>
			<Typography variant="subtitle2" fontWeight="bold">Institution info</Typography>
			<Divider/>
			<Box mt={1}>
				<Typography variant="body2">
					{activity.institution?.name}
				</Typography>
			</Box>
			<Box mt={1}>
				<Typography variant="body2">
					{address}
				</Typography>
			</Box>
		</Box>
	);
}