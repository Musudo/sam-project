import React, {Fragment, SyntheticEvent, useState} from "react";
import {
	Autocomplete,
	AutocompleteRenderInputParams,
	Chip,
	FormControl,
	FormHelperText,
	Grid,
	InputLabel,
	MenuItem,
	Select,
	SelectChangeEvent,
	TextField
} from "@mui/material";
import {IInstitution} from "../../../models/IInstitution";
import {IContact} from "../../../models/IContact";
import {JobTitlesEnum} from "../../../enums/JobTitlesEnum";
import {VALID_EMAIL_REGEXP} from '../../../constants/constants';
import {FormTypesEnum} from "../../../enums/ComponentPropsEnums";

interface Props {
	register: any;
	errors: any;
	setValue: any;
	control: any;
	institutions: IInstitution[] | [];
	currentInstitutions?: IInstitution[];
	type: FormTypesEnum;
	contact: IContact | null;
}

export function ContactForm(props: Props) {
	const [title, setTitle] = useState("");
	const jobTitles = Object.keys(JobTitlesEnum);

	function handleAutoComplete(event: any, value: string[]) {
		let schoolsTemp: any = [];

		// first fill new added institutions
		value.forEach((name: string) => {
			schoolsTemp.push(props.institutions.filter(i => i.name === name)[0]);
		});

		// then also fill institutions already where there
		props.contact?.institutions.forEach(i => {
			schoolsTemp.push(i)
		});

		props.setValue("institutions", schoolsTemp);
	}

	const handleChange = (event: SelectChangeEvent) => setTitle(event.target.value as string);

	return (
		<Grid container spacing={3}>
			<Grid item xs={12}>
				<TextField
					id="firstName2"
					label="First name"
					InputLabelProps={{shrink: true}}
					fullWidth
					autoComplete="given-name"
					variant="standard"
					{...props.register("firstName", {
						required: "First name is required",
						minLength: {value: 2, message: "Name must be longer than 1 character"}
					})}
				/>
				<FormHelperText error>{props?.errors?.firstName?.message}</FormHelperText>
			</Grid>
			<Grid item xs={12}>
				<TextField
					id="lastName"
					label="Last name"
					InputLabelProps={{shrink: true}}
					fullWidth
					autoComplete="family-name"
					variant="standard"
					{...props.register("lastName", {
						required: "Last name is required",
						minLength: {value: 2, message: "Last name must be longer than 1 character"}
					})}
				/>
				<FormHelperText error>{props?.errors?.lastName?.message}</FormHelperText>
			</Grid>
			<Grid item xs={12} sm={6}>
				<TextField
					id="email1"
					label="Email 1"
					InputLabelProps={{shrink: true}}
					fullWidth
					autoComplete="shipping address-line1"
					variant="standard"
					{...props.register("email1", {
						required: "Email 1 is required",
						pattern: {
							value: VALID_EMAIL_REGEXP,
							message: "Email 1 is not a valid email"
						}
					})}
				/>
				<FormHelperText error>{props?.errors?.email1?.message}</FormHelperText>
			</Grid>
			<Grid item xs={12} sm={6}>
				<TextField
					id="email2"
					label="Email 2"
					InputLabelProps={{shrink: true}}
					fullWidth
					autoComplete="shipping address-line2"
					variant="standard"
					{...props.register("email2", {
						pattern: {
							value: VALID_EMAIL_REGEXP,
							message: "Email 2 is not a valid email"
						}
					})}
				/>
				<FormHelperText error>{props?.errors?.email2?.message}</FormHelperText>
			</Grid>
			<Grid item xs={12} sm={6}>
				<TextField
					id="phone1"
					label="Phone 1"
					InputLabelProps={{shrink: true}}
					fullWidth
					autoComplete="shipping address-level2"
					variant="standard"
					{...props.register("phoneNumber1", {
						required: "Phone number 1 is required",
						minLength: {value: 6, message: "Phone number 1 is too short"}
					})}
				/>
				<FormHelperText error>{props?.errors?.phoneNumber1?.message}</FormHelperText>
			</Grid>
			<Grid item xs={12} sm={6}>
				<TextField
					id="phone2"
					label="Phone number 2"
					InputLabelProps={{shrink: true}}
					fullWidth
					variant="standard"
					{...props.register("phoneNumber2", {
						minLength: {value: 6, message: "Phone number 2 is too short"}
					})}
				/>
				<FormHelperText error>{props?.errors?.phoneNumber2?.message}</FormHelperText>
			</Grid>
			<Grid item xs={12}>
				<FormControl variant="standard" sx={{minWidth: 120}} fullWidth>
					<InputLabel id="jobTitleLabel" shrink>Job title</InputLabel>
					{props.type === FormTypesEnum.Create && (
						<Select
							labelId="jobTitleLabel"
							id="jobTitle"
							label="Job title"
							// displayEmpty
							value={title}
							// defaultValue=""
							{...props.register("jobTitle", {required: "Job title is required"})}
							onChange={handleChange}
						>
							<MenuItem key={0} value="">
								<em>None</em>
							</MenuItem>
							{jobTitles.map((jobTitle: string, index: number) => (
								<MenuItem key={index + 1} value={jobTitle}>
									{jobTitle}
								</MenuItem>
							))}
						</Select>
					)}
					{(props.type === FormTypesEnum.Edit && props.contact) && (
						<Select
							labelId="jobTitleLabel"
							id="jobTitle"
							label="Job title"
							InputLabelProps={{shrink: true}}
							displayEmpty
							defaultValue={props.contact?.jobTitle}
							{...props.register("jobTitle", {required: "Job title is required"})}
							onChange={(event: SelectChangeEvent) => props.setValue("jobTitle", event.target.value)}
						>
							<MenuItem key={0} value={props.contact?.jobTitle}>
								<em>{props.contact?.jobTitle}</em>
							</MenuItem>
							{
								jobTitles.map((jobTitle: string, index: number) => (
									<MenuItem key={index + 1}
											  value={jobTitle}>{jobTitle}
									</MenuItem>
								))
							}
						</Select>
					)}
					<FormHelperText
						error>{props?.errors?.jobTitle?.message}</FormHelperText>
				</FormControl>
			</Grid>
			<Grid item xs={12}>
				{props.contact && props.contact.institutions.map((i: IInstitution, index: number) => (
					<Fragment key={index}>
						<Chip key={i.id} label={i.name}/> {' '}
					</Fragment>
				))}
				<FormControl variant="standard" sx={{minWidth: 120}} fullWidth>
					<Autocomplete
						multiple
						options={props.institutions.length > 0 ? props.institutions.map((i: IInstitution) => i.name) : []}
						// options={[]}
						renderInput={(params: AutocompleteRenderInputParams) => (
							<TextField
								{...params}
								variant="standard"
								label="Institution"
								InputLabelProps={{children: null}} // otherwise Textfield gives error because of params
							/>
						)}
						onChange={(event: SyntheticEvent, value: any) => handleAutoComplete(event, value)}
					/>
				</FormControl>
			</Grid>
		</Grid>
	);
}