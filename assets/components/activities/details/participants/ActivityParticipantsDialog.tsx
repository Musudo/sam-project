import * as React from 'react';
import {Dispatch, SetStateAction, SyntheticEvent, useEffect, useState} from 'react';
import Button from '@mui/material/Button';
import Dialog from '@mui/material/Dialog';
import DialogActions from '@mui/material/DialogActions';
import DialogContent from '@mui/material/DialogContent';
import DialogContentText from '@mui/material/DialogContentText';
import DialogTitle from '@mui/material/DialogTitle';
import {useForm} from "react-hook-form";
import AddIcon from "@mui/icons-material/Add";
import {fetchData, patchData, postData} from "../../../../utils/HttpRequestUtil";
import {
	Autocomplete,
	AutocompleteRenderInputParams,
	FormControl,
	FormHelperText,
	Grid,
	InputAdornment,
	TextField
} from "@mui/material";
import {IContact} from "../../../../models/IContact";
import EmailIcon from '@mui/icons-material/Email';
import PersonIcon from '@mui/icons-material/Person';
import {IActivity} from "../../../../models/IActivity";
import {VALID_EMAIL_REGEXP} from "../../../../constants/constants";
import {ParticipantTypesEnum} from "../../../../enums/ComponentPropsEnums";

interface Props {
	activity: IActivity;
	type: ParticipantTypesEnum;
	setIsUpdated: Dispatch<SetStateAction<boolean>>
}

export function ActivityParticipantsDialog({activity, type, setIsUpdated}: Props) {
	const [contacts, setContacts] = useState<IContact[]>([]);
	// TODO: find a way to properly validate autocomplete, or replace it by select
	const [isDisabled, setIsDisabled] = useState(true);

	const {register, handleSubmit, setValue, formState: {errors}} = useForm<{
		contact: number;
		email: string;
	}>({
		defaultValues: {
			contact: 0,
			email: "",
		}
	});

	useEffect(() => {
		fetchData(`/contacts/institution-guid-name/${activity.institution?.guid}`)
			.then((response) => setContacts(response!.data))
			.catch(() => console.error('Failed to fetch contacts'));
	}, []);

	function onSubmit(data: any) {
		if (type === ParticipantTypesEnum.Participant) {
			delete data['email'];

			patchData(`/activities/${activity.id}/participant`, data)
				.then(() => setIsUpdated((current: boolean) => !current))
				.catch(() => console.error('Failed to patch participant'));
		} else if (type === ParticipantTypesEnum.External_Participant) {
			delete data['contact'];

			postData(`/external-participants/activity/${activity.id}`, data)
				.then(() => setIsUpdated((current: boolean) => !current))
				.catch(() => console.error('Failed to post external participant'));
		}
	}

	/**
	 * dialog configuration
	 */
	const [open, setOpen] = useState(false);

	const handleClickOpen = () => {
		setOpen(true);
	}

	const handleClose = () => {
		setOpen(false);
		setIsDisabled(true);
	};

	return (
		<>
			<Button variant="contained"
					size="small"
					onClick={handleClickOpen}
					startIcon={<AddIcon/>}>
				{type === ParticipantTypesEnum.Participant ? "Add Participant" : "Add Ext. Participant"}
			</Button>
			<Dialog open={open} onClose={handleClose} fullWidth>
				<form onSubmit={handleSubmit(onSubmit)}>
					{
						type === ParticipantTypesEnum.Participant ? (
							<>
								<DialogTitle>Add new participant</DialogTitle>
								<DialogContent sx={{height: "10rem"}}>
									<DialogContentText>
										Add new participant from you contacts.
										<br/>There will be sent a confirmation email to this participant.
									</DialogContentText>
									<Grid item xs={12} mt={2}>
										<FormControl variant="standard" fullWidth sx={{minWidth: 120}}>
											<Autocomplete
												disablePortal
												options={contacts ? contacts.map((c: IContact) => c.firstName + " " + c.lastName) : []}
												renderInput={(params: AutocompleteRenderInputParams) => (
													<TextField {...params}
															   label="Contact"
															   variant="standard"
															   InputProps={{
																   ...params.InputProps,
																   startAdornment: (
																	   <InputAdornment
																		   position="start">
																		   <PersonIcon
																			   color="primary"/>
																	   </InputAdornment>)
															   }}
															   InputLabelProps={{children: null}}/> // otherwise Textfield gives error because of params
												)}
												onChange={(event: SyntheticEvent, value: any) => {
													setValue("contact", contacts.find(c => c.firstName + " " + c.lastName === value)?.id ?? 0);
													setIsDisabled(false);
												}}
											/>
										</FormControl>
									</Grid>
								</DialogContent>
							</>
						) : (
							<>
								<DialogTitle>Add new external participant</DialogTitle>
								<DialogContent>
									<DialogContentText>
										Add email address of external person. You can later add him to your contacts
										if you want to.
										<br/>
										There will be sent a confirmation email to this participant.
									</DialogContentText>
									<Grid item xs={12} mt={2}>
										<TextField
											label="Email"
											variant="standard"
											fullWidth
											InputProps={{
												startAdornment: (
													<InputAdornment position="start">
														<EmailIcon color="primary"/>
													</InputAdornment>
												)
											}}
											{...register("email", {
												required: "Email is required",
												pattern: {
													value: VALID_EMAIL_REGEXP,
													message: "Email is not a valid email"
												}
											})}
										/>
										<FormHelperText error>{errors.email && errors.email.message}</FormHelperText>
									</Grid>
								</DialogContent>
							</>
						)
					}
					<DialogActions>
						<Button type="button" size="small" variant="text" onClick={handleClose}>Close</Button>
						{type === ParticipantTypesEnum.Participant ? (
							<Button type="submit" size="small" variant="contained" disabled={isDisabled}>
								Add
							</Button>
						) : (
							<Button type="submit" size="small" variant="contained">Add</Button>
						)}
					</DialogActions>
				</form>
			</Dialog>
		</>
	);
}