import React, {Fragment, useMemo, useRef, useState} from 'react';
import {useForm} from 'react-hook-form';
import {Checkbox, IconButton, InputAdornment, List, ListItem, ListItemIcon, TextField} from '@mui/material';
import DeleteIcon from '@mui/icons-material/Delete';
import {
	deleteDataReactQuery,
	fetchDataReactQuery,
	patchDataReactQuery,
	postDataReactQuery
} from '../../../utils/HttpRequestUtil';
import SendIcon from '@mui/icons-material/Send';
import {debounce} from 'lodash';
import {useMutation, useQuery, useQueryClient} from '@tanstack/react-query';
import {ITask} from '../../../models/ITask';

interface Props {
	activityId: number;
	activityGuid: string;
}

export function ActivityTasks({activityId, activityGuid}: Props) {
	const queryClient = useQueryClient();

	const {register, handleSubmit, resetField} = useForm({
		defaultValues: {
			description: null,
			completed: false,
			activity: activityId
		}
	});

	const {data: tasks} = useQuery<ITask[]>(
		['tasks'],
		() => fetchDataReactQuery(`/tasks/activities/${activityGuid}`)
	);

	const createTaskMutation = useMutation(
		{
			mutationFn: (task: object) => postDataReactQuery(`/tasks`, task),
			onSuccess: () => {
				queryClient.invalidateQueries({queryKey: ['tasks']});
			}
		}
	);

	const handleCreateTask = async (data: object) => {
		createTaskMutation.mutate(data);
		resetField('description');
	}

	const modifyTaskMutation = useMutation(
		{
			mutationFn: (task: any) => patchDataReactQuery(`/tasks/${task.id}`, task),
			onSuccess: () => {
				queryClient.invalidateQueries({queryKey: ['tasks']});
			}
		}
	);

	const handleChangeTask = (event: any, task: any) => {
		const {value} = event.target;
		task.description = value;
		delete task.created;

		modifyTaskMutation.mutate(task);
	}

	const debouncedChangeTaskHandler = useMemo(
		() => debounce(handleChangeTask, 300)
		, []);

	const handleCheckTask = (task: any) => {
		task.completed = !task.completed;
		delete task.created;

		modifyTaskMutation.mutate(task);
	}

	const deleteTaskMutation = useMutation(
		{
			mutationFn: (id: number) => deleteDataReactQuery(`/tasks/${id}`),
			onSuccess: () => {
				queryClient.invalidateQueries({queryKey: ['tasks']});
			}
		}
	);

	const handleDeleteTask = (id: number) => deleteTaskMutation.mutate(id);

	return (
		<List sx={{width: '100%'}}>
			<Fragment key={1}>
				<TextField placeholder="New task"
						   fullWidth
						   variant="standard"
						   sx={{marginBottom: 2}}
						   InputProps={{
							   endAdornment: (
								   <InputAdornment position="start">
									   <IconButton onClick={(event) => handleSubmit(handleCreateTask)(event)}>
										   <SendIcon/>
									   </IconButton>
								   </InputAdornment>
							   )
						   }}
						   {...register("description")}
						   onKeyDown={(event) => {
							   if (event.key === "Enter") {
								   handleSubmit(handleCreateTask)(event);
							   }
						   }}
				/>
				{tasks && tasks.map((task: ITask) => (
					<ListItem
						secondaryAction={
							<IconButton edge="end" aria-label="delete task">
								<DeleteIcon sx={{opacity: 0.5}} onClick={() => handleDeleteTask(task.id)}/>
							</IconButton>
						}
						disablePadding
						divider
					>
						<ListItemIcon>
							<Checkbox
								edge="start"
								checked={task.completed}
								color="success"
								disableRipple
								onChange={() => handleCheckTask(task)}
							/>
						</ListItemIcon>
						<TextField variant="standard"
								   InputProps={{disableUnderline: true}}
								   defaultValue={task.description}
								   sx={{opacity: task.completed ? "0.6" : "1", minWidth: "75%", marginLeft: "-25px"}}
								   onKeyDown={(event) => debouncedChangeTaskHandler(event, task)}/>
					</ListItem>
				))}
			</Fragment>
		</List>
	);
}