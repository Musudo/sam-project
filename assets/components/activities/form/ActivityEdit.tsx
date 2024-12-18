import React, {useEffect, useState} from "react";
import {ActivityForm} from "./ActivityForm";
import {Box, Button, Container, Paper, Typography} from "@mui/material";
import {useForm} from "react-hook-form";
import dayjs from "dayjs";
import {useParams} from "react-router-dom";
import {IActivity} from "../../../models/IActivity";
import {fetchDataReactQuery, patchDataReactQuery} from "../../../utils/HttpRequestUtil";
import {FormSubmitSnackbar} from "../../FormSubmitSnackbar";
import {ITag} from "../../../models/ITag";
import utc from "dayjs/plugin/utc";
import timezone from "dayjs/plugin/timezone";
import {useMutation, useQuery} from "@tanstack/react-query";
import LoadingComponent from "../../LoadingComponent";
import {ErrorComponent} from "../../ErrorComponent";
import {ErrorTypesEnum} from "../../../enums/ErrorTypesEnum";
import {FormTypesEnum} from "../../../enums/ComponentPropsEnums";

dayjs.extend(utc);
dayjs.extend(timezone);

export function ActivityEdit() {
	const [openSnackbar, setOpenSnackbar] = useState(false);
	const {guid} = useParams();

	const {data: activity, status: activityStatus} = useQuery<IActivity>(
		['activity'],
		() => fetchDataReactQuery(`/activities/${guid}`),
	);

	useEffect(() => {
		if (activityStatus === 'success' && activity) reset(activity);
	}, [activityStatus, activity]);

	const {data: tags} = useQuery<ITag[]>(
		['tags'],
		() => fetchDataReactQuery(`/tags`)
	);

	const {register, control, handleSubmit, reset, resetField, setValue, formState: {errors}} = useForm<IActivity>({
		defaultValues: {
			subject: activity?.subject ?? "",
			tags: activity?.tags,
			externalNote: activity?.externalNote ?? "",
			internalNote: activity?.internalNote ?? "",
			type: activity?.type ?? "",
			start: activity?.start,
			end: activity?.end
		}
	});

	function onSubmit(newActivity: IActivity) {
		// format start date and adjust its timezone
		newActivity.start = dayjs(newActivity.start).tz('UTC').format('DD-MM-YYYY HH:mm');
		// format start date and adjust its timezone
		newActivity.end = dayjs(newActivity.end).tz('UTC').format('DD-MM-YYYY HH:mm');

		modifyActivityMutation.mutate(newActivity);
	}

	const modifyActivityMutation = useMutation(
		{
			mutationFn: (data: IActivity) => patchDataReactQuery(`/activities/${activity?.id}`, data),
			onSuccess: () => {
				setOpenSnackbar(true);
			}
		}
	);

	if (activityStatus === 'loading') return <LoadingComponent/>;

	if (activityStatus === 'error') return <ErrorComponent type={ErrorTypesEnum.General}/>;

	return (
		<Container component="main" maxWidth="sm" sx={{mb: 4}}>
			<FormSubmitSnackbar setOpen={setOpenSnackbar} open={openSnackbar} message="Activity updated!"/>
			<form onSubmit={handleSubmit(onSubmit)}>
				<Paper variant="outlined" sx={{my: {xs: 3, md: 6}, p: {xs: 2, md: 3}}}>
					<Typography component="h1" variant="h4" align="center">
						Edit Activity
					</Typography>
					{activity && <ActivityForm register={register} controller={control} errors={errors}
											   tags={tags ?? []} activity={activity} setValue={setValue}
											   type={FormTypesEnum.Edit}/>}
					<Box sx={{display: 'flex', justifyContent: 'flex-end'}}>
						<Button
							type="submit"
							variant="contained"
							sx={{mt: 3, ml: 1}}>
							Save
						</Button>
					</Box>
				</Paper>
			</form>
		</Container>
	);
}