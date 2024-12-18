import React, {Dispatch, SetStateAction, useEffect} from "react";
import {Box, Chip, FormControl, FormHelperText, Grid, InputLabel, MenuItem, OutlinedInput, Select} from "@mui/material";
import {Controller} from "react-hook-form";
import {IContact} from "../../../models/IContact";
import {IInstitution} from "../../../models/IInstitution";
import {fetchData, fetchDataReactQuery} from "../../../utils/HttpRequestUtil";
import InstitutionSearchBar from "../overview/InstitutionSearchBar";
import {useTranslation} from "react-i18next";
import {MenuProps} from "../../../props/MUIElementProps";
import {useQuery} from "@tanstack/react-query";
import {IActivity} from "../../../models/IActivity";

interface Props {
	control: any,
	errors: any;
	setValue: any;
	contacts: IContact[];
	setContacts: Dispatch<SetStateAction<IContact[]>>;
	institution: IInstitution | null;
	setInstitution: Dispatch<SetStateAction<IInstitution | null>>;
}

export function ParticipantForm(props: Props) {
	const {t} = useTranslation();

	const {data: contacts, status: contactsStatus} = useQuery<IContact[]>(
		['contacts', props.institution],
		() => {
			if (props.institution) {
				return fetchDataReactQuery(`/contacts/institution-guid-name/${props.institution?.guid}`);
			} else {
				return [];
			}
		}
	);

	useEffect(() => {
		if (contactsStatus === 'success') {
			props.setContacts(contacts);
			props.setValue('institution', props.institution?.id);
		}
	}, [contacts, contactsStatus]);

	// selected chips data of multiselect
	let contactsObj: any = [];
	if (props.contacts && props.contacts.length > 0) {
		props.contacts?.map((c: IContact) => contactsObj[c.id] = c.firstName + " " + c.lastName);
	} else {
		contactsObj = [];
	}

	return (
		<>
			<Grid item xs={12}>
				<FormControl sx={{width: "100%"}}>
					<InstitutionSearchBar setInstitution={props.setInstitution} institution={props.institution}
										  setContacts={props.setContacts} setValue={props.setValue}/>
				</FormControl>
			</Grid>
			<Grid item xs={12}>
				<FormControl fullWidth sx={{minWidth: 120}}>
					<InputLabel id="contactLabelId">{t('Activity form.Participants')}</InputLabel>
					<Controller
						name="contacts"
						control={props.control}
						rules={{required: "Participants required"}}
						render={({field}) => (
							<Select
								{...field}
								labelId="contactLabelId"
								id="contactsId"
								multiple
								input={<OutlinedInput label={t('Activity form.Participants')}/>}
								renderValue={(selected) => (
									<Box sx={{display: 'flex', flexWrap: 'wrap', gap: 0.5}}>
										{selected.map((value: any) => (
											contactsObj[value] && <Chip key={value} label={contactsObj[value]}/>
										))}
									</Box>
								)}
								MenuProps={MenuProps}
							>
								{props.contacts.length > 0 && props.contacts.map((contact: IContact) => (
									<MenuItem
										key={contact.id}
										value={contact.id}
									>
										{contact.firstName} {contact.lastName}
									</MenuItem>
								))}
							</Select>
						)}
					/>
				</FormControl>
				<FormHelperText error>{props?.errors?.contacts?.message}</FormHelperText>
			</Grid>
		</>
	);
}