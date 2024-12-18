import React from "react";
import {Box, CardActionArea, Paper, Typography} from "@mui/material";
import {IActivity} from "../../../models/IActivity";
import {useNavigate} from "react-router-dom";
import PersonIcon from '@mui/icons-material/Person';
import PeopleIcon from '@mui/icons-material/People';
import ConnectWithoutContactIcon from '@mui/icons-material/ConnectWithoutContact';
import LocalPhoneIcon from '@mui/icons-material/LocalPhone';
import TaskAltIcon from '@mui/icons-material/TaskAlt';
import RateReviewIcon from '@mui/icons-material/RateReview';
import EmailIcon from '@mui/icons-material/Email';
import dayjs from "dayjs";
import {useTranslation} from "react-i18next";
import utc from "dayjs/plugin/utc";
import timezone from "dayjs/plugin/timezone";
import {ActivityTypesEnum} from "../../../enums/ActivityTypesEnum";
import AssignmentIcon from '@mui/icons-material/Assignment';

dayjs.extend(utc);
dayjs.extend(timezone);

interface Props {
	activity: IActivity;
}

export function ActivityCard({activity}: Props) {
	const navigate = useNavigate();
	const {t} = useTranslation();

	const renderIcon = () => {
		if (activity.type === ActivityTypesEnum.Phone) {
			return (
				<LocalPhoneIcon color="secondary"/>
			);
		} else if (activity.type === ActivityTypesEnum.Online) {
			return (
				<ConnectWithoutContactIcon color="secondary"/>
			);
		} else if (activity.type === ActivityTypesEnum.Physical) {
			return (
				<PeopleIcon color="secondary"/>
			);
		}
	}

	return (
		<CardActionArea onClick={() => navigate(`/activities/${activity.guid}`)}>
			<Paper
				sx={{
					height: 140,
					width: 265,
					display: 'flex',
					flexDirection: 'column',
					// justifyContent: 'space-between',
					padding: '1em',
				}}
				elevation={1}
			>
				<Box display="flex" justifyContent="space-between">
					<Box sx={{flexGrow: 0.5}}>
					</Box>
					<Box display="flex" flexDirection="column" alignItems="center">
						{renderIcon()}
						<Box textAlign="center" marginTop={1}>
							<Typography variant="subtitle2">
								{activity.subject}
							</Typography>
							<Typography variant="caption" color="textSecondary">
								{dayjs(activity.start).tz('UTC').format('DD MMM YYYY HH:mm')}
							</Typography>
						</Box>
					</Box>
					<Box>
						<AssignmentIcon color={!activity.review ? "error" : "success"}/>
						<EmailIcon color={!activity.emailSentAt ? "error" : "success"}/>
					</Box>
				</Box>
				<Box display="flex" justifyContent="space-around" width="100%">
					<Box display="flex" alignItems="center">
						<PersonIcon color="disabled" sx={{mr: 1}}/>
						<Box>
							<Typography variant="subtitle2" sx={{mb: -1}}>
								{activity.externalParticipants
									? activity.contacts?.length + activity.externalParticipants?.length
									: activity.contacts?.length}
							</Typography>
							<Typography variant="caption" color="textSecondary">
								{t('Activities overview page.Card.Participants')}
							</Typography>
						</Box>
					</Box>
					<Box display="flex" alignItems="center">
						<TaskAltIcon color="disabled" sx={{mr: 1}}/>
						<Box>
							<Typography variant="subtitle2" sx={{mb: -1}}>
								{activity.tasks?.filter(t => t.completed).length}/{activity.tasks?.length}
							</Typography>
							<Typography variant="caption" color="textSecondary">
								{t('Activities overview page.Card.Tasks')}
							</Typography>
						</Box>
					</Box>
				</Box>
			</Paper>
		</CardActionArea>
	);
}