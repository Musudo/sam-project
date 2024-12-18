import React from "react";
import Typography from '@mui/material/Typography';
import {Grid} from "@mui/material";
import {IActivity} from "../../models/IActivity";
import ConnectWithoutContactIcon from '@mui/icons-material/ConnectWithoutContact';
import LocalPhoneIcon from '@mui/icons-material/LocalPhone';
import PeopleIcon from '@mui/icons-material/People';
import dayjs from "dayjs";
import {useTranslation} from "react-i18next";
import isToday from "dayjs/plugin/isToday";
import isBetween from "dayjs/plugin/isBetween";
// import { DateCalendar } from '@mui/x-date-pickers/DateCalendar';
import { LocalizationProvider } from "@mui/x-date-pickers";
import { AdapterDayjs } from "@mui/x-date-pickers/AdapterDayjs";

dayjs.extend(isToday);
dayjs.extend(isBetween);

interface Props {
	physical: IActivity[];
	phone: IActivity[];
	remote: IActivity[];
}

export function Summary({physical, phone, remote}: Props) {
	const {t} = useTranslation();

	return (
		<>
			<Typography component="h2" variant="h6" color="primary" gutterBottom>
				{t('Dashboard page.Summary')}
			</Typography>
			<Grid container>
				{/*<LocalizationProvider*/}
				{/*	dateAdapter={AdapterDayjs}*/}
				{/*	localeText={{*/}
				{/*		// @ts-ignore*/}
				{/*		calendarWeekNumberHeaderText: '#',*/}
				{/*		calendarWeekNumberText: (weekNumber: number) => `${weekNumber}.`,*/}
				{/*	}}*/}
				{/*>*/}
				{/*<DateCalendar displayWeekNumber />*/}
				{/*</LocalizationProvider>*/}
				<Grid item xs>
					<Typography component="p" variant="h6">
						{t('Dashboard page.Summary card.Monthly')}
					</Typography>
					<Typography color="text.secondary" sx={{flex: 1}} align="center" marginBottom={1}>
						<LocalPhoneIcon/> {phone.filter(activity => dayjs(activity.start).isBetween(dayjs().add(8, 'day'), dayjs().add(1, 'month'), 'day', '[)')).length}
					</Typography>
					<Typography color="text.secondary" sx={{flex: 1}} align="center" marginBottom={1}>
						<PeopleIcon/> {physical.filter(activity => dayjs(activity.start).isBetween(dayjs().add(8, 'day'), dayjs().add(1, 'month'), 'day', '[)')).length}
					</Typography>
					<Typography color="text.secondary" sx={{flex: 1}} align="center" marginBottom={1}>
						<ConnectWithoutContactIcon/> {remote.filter(activity => dayjs(activity.start).isBetween(dayjs().add(8, 'day'), dayjs().add(1, 'month'), 'day', '[)')).length}
					</Typography>
				</Grid>
				<Grid item xs>
					<Typography component="p" variant="h6">
						{t('Dashboard page.Summary card.Weekly')}
					</Typography>
					<Typography color="text.secondary" sx={{flex: 1}} align="center" marginBottom={1}>
						<LocalPhoneIcon/> {phone.filter(activity => dayjs(activity.start).isBetween(dayjs().add(1, 'day'), dayjs().add(8, 'day'), 'day', '[)')).length}
					</Typography>
					<Typography color="text.secondary" sx={{flex: 1}} align="center" marginBottom={1}>
						<PeopleIcon/> {physical.filter(activity => dayjs(activity.start).isBetween(dayjs().add(1, 'day'), dayjs().add(8, 'day'), 'day', '[)')).length}
					</Typography>
					<Typography color="text.secondary" sx={{flex: 1}} align="center" marginBottom={1}>
						<ConnectWithoutContactIcon/> {remote.filter(activity => dayjs(activity.start).isBetween(dayjs().add(1, 'day'), dayjs().add(8, 'day'), 'day', '[)')).length}
					</Typography>
				</Grid>
				<Grid item xs>
					<Typography component="p" variant="h6">
						{t('Dashboard page.Summary card.Daily')}
					</Typography>
					<Typography color="text.secondary" sx={{flex: 1}} align="center" marginBottom={1}>
						<LocalPhoneIcon/> {phone.filter(activity => dayjs(activity.start).isToday()).length}
					</Typography>
					<Typography color="text.secondary" sx={{flex: 1}} align="center" marginBottom={1}>
						<PeopleIcon/> {physical.filter(activity => dayjs(activity.start).isToday()).length}
					</Typography>
					<Typography color="text.secondary" sx={{flex: 1}} align="center" marginBottom={1}>
						<ConnectWithoutContactIcon/> {remote.filter(activity => dayjs(activity.start).isToday()).length}
					</Typography>
				</Grid>
			</Grid>
		</>
	);
}