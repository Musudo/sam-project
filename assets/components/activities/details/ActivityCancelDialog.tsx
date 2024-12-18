import React, {Dispatch, SetStateAction} from 'react';
import {Button, Dialog, DialogActions, DialogContent, DialogContentText, DialogTitle} from "@mui/material";
import {deleteData, postData} from "../../../utils/HttpRequestUtil";
import {IActivity} from "../../../models/IActivity";
import {useNavigate} from "react-router-dom";

interface Props {
	open: boolean;
	setOpen: Dispatch<SetStateAction<boolean>>
	setIsUpdated: Dispatch<SetStateAction<boolean>>
	activity: IActivity;
}

export function ActivityCancelDialog(props: Props) {
	const navigate = useNavigate();

	const cancelActivity = (sendEmail: boolean) => {
		// prepare activity data object to send it to backend
		const data = {activity: props.activity}

		deleteData(`/activities/${props.activity.id}`)
			.then((response) => {
				if (response?.status === 200 && sendEmail) {
					postData(`/email/activity/cancel`, data)
						.catch(() => console.error("Failed to send email"));
					props.setIsUpdated(true);
				}
			})
			.then(() => navigate(`/activities`))
			.catch(() => console.error("Failed to delete activity"));
	}

	const handleClose = () => props.setOpen(false);

	return (
		<Dialog
			open={props.open}
			onClose={handleClose}
			aria-labelledby="alert-dialog-title"
			aria-describedby="alert-dialog-description"
		>
			<DialogTitle id="alert-dialog-title">
				Delete activity '{props.activity.subject}'
			</DialogTitle>
			<DialogContent>
				<DialogContentText id="alert-dialog-description">
					You are about to delete this activity.
					<br/>
					Do you want also to send a cancellation email to participants?
				</DialogContentText>
			</DialogContent>
			<DialogActions>
				<Button variant="contained" onClick={handleClose} size="small">
					Don't delete
				</Button>
				<Button variant="text" onClick={() => cancelActivity(true)} size="small">
					Send email
				</Button>
				<Button variant="text" onClick={() => cancelActivity(false)} size="small">
					Just delete
				</Button>
			</DialogActions>
		</Dialog>
	);
}