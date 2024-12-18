import React from "react";
import Typography from '@mui/material/Typography';
import {Grid, List, ListItem, ListItemIcon, ListItemText} from "@mui/material";
import {ITask} from "../../models/ITask";
import dayjs from "dayjs";
import {IActivity} from "../../models/IActivity";
import TaskAltIcon from '@mui/icons-material/TaskAlt';
import {t} from "i18next";
import {TaskTypesEnum} from "../../enums/ComponentPropsEnums";

interface Props {
	tasks: ITask[];
	activities: IActivity[];
	type: TaskTypesEnum
}

export function HotTasks({tasks, type, activities}: Props) {
	let hotTasks = tasks;

	if (hotTasks.length > 0 && type === TaskTypesEnum.Newest) {
		hotTasks.sort((a: ITask, b: ITask) => dayjs(a.created).unix() - dayjs(b.created).unix());
	} else if (hotTasks.length > 0 && TaskTypesEnum.Oldest) {
		hotTasks.sort((a: ITask, b: ITask) => dayjs(a.created).unix() - dayjs(b.created).unix()).reverse();
	}

	return (
		<>
			<Typography component="h2" variant="h6" color="primary" gutterBottom>
				{type === TaskTypesEnum.Newest ? <>{t('Dashboard page.Newest activities')}</> : <>{t('Dashboard page.Oldest activities')}</>}
			</Typography>
			<Grid container>
				{
					(type === TaskTypesEnum.Newest && hotTasks.length > 0) && hotTasks.slice(0, 4).map(task => (
						<List sx={{width: '100%', maxWidth: 800, bgcolor: 'background.paper'}} dense disablePadding>
							<ListItem key={task.id}>
								<ListItemIcon>
									<TaskAltIcon/>
								</ListItemIcon>
								<ListItemText primary={
									<Typography sx={{overflow: 'hidden'}}>
										{task.description}
									</Typography>}
											  secondary={
												  <span>{activities && activities.find(a => a.id === task.activity?.id)?.subject}</span>
											  }
								/>
							</ListItem>
						</List>
					))
				}
				{
					(type === TaskTypesEnum.Oldest && hotTasks.length > 0) && hotTasks.slice(0, 4).map(task => (
						<List sx={{width: '100%', maxWidth: 800, bgcolor: 'background.paper'}} dense disablePadding>
							<ListItem key={task.id}>
								<ListItemIcon>
									<TaskAltIcon/>
								</ListItemIcon>
								<ListItemText primary={
									<Typography sx={{overflow: 'hidden'}}>
										{task.description}
									</Typography>}
											  secondary={
												  <span>{activities && activities.find(a => a.id === task.activity?.id)?.subject}</span>
											  }
								/>
							</ListItem>
						</List>
					))
				}
			</Grid>
		</>
	);
}